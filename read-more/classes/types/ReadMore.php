<?php
namespace arm;

abstract class ReadMore {
	private $id;
	private $savedObj = false;
	private $sanitizedData = [];

	public function setId($id) {
		$this->id = (int)$id;
	}

	public function getId() {
		return $this->id;
	}

	public function setSavedObj($savedObj) {
		if (!empty($savedObj)) {
			$this->savedObj = (object)$savedObj;
		}
	}

	public function getSavedObj() {
		return $this->savedObj;
	}

	public function insertIntoSanitizedData($sanitizedData) {
		if (!empty($sanitizedData)) {
			$this->sanitizedData[$sanitizedData['name']] = $sanitizedData['value'];
		}
	}

	public function getSanitizedData() {
		return $this->sanitizedData;
	}

	public static function getTypePathFormType($type) {
		global $ARM_TYPES;
		$typePath = '';
		if (!empty($ARM_TYPES['typePath'][$type])) {
			$typePath = $ARM_TYPES['typePath'][$type];
		}

		return $typePath;
	}

	public static function getTypeClassName($type) {
		$typeName = ucfirst(strtolower($type));
		$className = $typeName.'ReadMore';

		return $className;
	}

	public static function createObj() {
		$id = self::findId();
		$type = self::getTypeName();
		$typePath = self::getTypePathFormType($type);
		$className = self::getTypeClassName($type);

		if (!file_exists($typePath.$className.'.php')) {
			wp_die(__($className.' class does not exist', YRM_TEXT_DOMAIN));
		}
		require_once($typePath.$className.'.php');
		$className = __NAMESPACE__.'\\'.$className;
		if (!class_exists($className)) {
			wp_die(__($className.' class does not exist', YRM_TEXT_DOMAIN));
		}

		$typeObj = new $className();
		$typeObj->setId($id);

		return $typeObj;
	}

	public static function findId() {
		$id = 0;

		if (!empty($_GET['readMoreId'])) {
			return (int)$_GET['readMoreId'];
		}

		return $id;
	}

	private static function getTypeName() {
		$type = 'button';

		/*
		 * First, we try to find the countdown type with the post id then,
		 * if the post id doesn't exist, we try to find it with $_GET['ycd_type']
		 */
		if (!empty($_GET['readMoreId'])) {
			$id = (int)$_GET['readMoreId'];
			$cdOptionsData = GeneralModel::findByID($id);
			if (!empty($cdOptionsData)) {
				$type = $cdOptionsData->getType();
			}
		}
		else if (!empty($_GET['arm_type'])) {
			$type = sanitize_text_field($_GET['arm_type']);
		}

		return $type;
	}

	// Get values more effectively
	/**
	 * Get option value from name
	 * @since 1.0.0
	 *
	 * @param string $optionName
	 * @param bool $forceDefaultValue
	 * @return string
	 */
	public function getOptionValue($optionName, $forceDefaultValue = false) {

		return $this->getOptionValueFromSavedData($optionName, $forceDefaultValue);
	}

	public function filterNameForObject($name) {
		return str_replace('-', '_', $name);
	}

	public function getOptionValueFromSavedData($optionName, $forceDefaultValue = false) {

		$defaultData = $this->getDefaultDataByName($optionName);
		$savedData = $this->getSavedObj();

		$optionValue = null;

		if (empty($defaultData['type'])) {
			$defaultData['type'] = 'string';
		}

		if (!empty($savedData)) { //edit mode
			$objectSearchName = $this->filterNameForObject($optionName);
			if (isset($savedData->$objectSearchName)) { //option exists in the database
				$optionValue = $savedData->$objectSearchName;
			}
			/* if it's a checkbox, it may not exist in the db
			 * if we don't care about it's existence, return empty string
			 * otherwise, go for it's default value
			 */
			else if ($defaultData['type'] == 'checkbox' && !$forceDefaultValue) {
				$optionValue = '';
			}
		}

		if (($optionValue === null && !empty($defaultData['defaultValue'])) || ($defaultData['type'] == 'number' && !isset($optionValue))) {
			$optionValue = $defaultData['defaultValue'];
		}

		if ($defaultData['type'] == 'checkbox') {
			$optionValue = $this->boolToChecked($optionValue);
		}

		if(isset($defaultData['ver']) && $defaultData['ver'] > YCD_PKG_VERSION) {
			if (empty($defaultData['allow'])) {
				return $defaultData['defaultValue'];
			}
			else if (!in_array($optionValue, $defaultData['allow'])) {
				return $defaultData['defaultValue'];
			}
		}

		return $optionValue;
	}

	public function getDefaultDataByName($optionName) {
		global $ARM_OPTIONS;

		if(empty($ARM_OPTIONS)) {
			return array();
		}
		foreach ($ARM_OPTIONS as $option) {
			if ($option['name'] == $optionName) {
				return $option;
			}
		}

		return array();
	}

	public static function create($data) {
		$obj = new static();

		foreach ($data as $name => $value) {
			$defaultData = $obj->getDefaultDataByName($name);
			if (empty($defaultData['type'])) {
				$defaultData['type'] = 'string';
			}

			$sanitizedValue = $obj->sanitizeValueByType($value, $defaultData['type']);
			$obj->insertIntoSanitizedData(array('name' => $name,'value' => $sanitizedValue));
		}
		$obj->save();
	}

	private function save() {
		$data = $this->getSanitizedData();

		$data = apply_filters('armSaveData', $data);
		$model = GeneralModel::findByID($data['read-more-id']);
		$model->setType($data['read-more-type']);
		$model->setExpm_title($data['expm-title']);
		$model->setButton_width($data['button-width']);
		$model->setButton_height($data['button-height']);
		$model->setAnimation_duration($data['animation-duration']);
		$model->setOptions(json_encode($data));
		$model->save();
	}

	public function sanitizeValueByType($value, $type) {
		switch ($type) {
			case 'string':
			case 'number':
				$sanitizedValue = sanitize_text_field($value);
				break;
			case 'html':
				$sanitizedValue = $value;
				break;
			case 'array':
				$sanitizedValue = $this->recursiveSanitizeTextField($value);
				break;
			case 'email':
				$sanitizedValue = sanitize_email($value);
				break;
			case "checkbox":
				$sanitizedValue = sanitize_text_field($value);
				break;
			default:
				$sanitizedValue = sanitize_text_field($value);
				break;
		}

		return $sanitizedValue;
	}

	public function recursiveSanitizeTextField($array) {
		if (!is_array($array)) {
			return $array;
		}

		foreach ($array as $key => &$value) {
			if (is_array($value)) {
				$value = $this->recursiveSanitizeTextField($value);
			}
			else {
				/*get simple field type and do sensitization*/
				$defaultData = $this->getDefaultDataByName($key);
				if (empty($defaultData['type'])) {
					$defaultData['type'] = 'string';
				}
				$value = $this->sanitizeValueByType($value, $defaultData['type']);
			}
		}

		return $array;
	}

	public function boolToChecked($var) {
		return ($var ? 'checked' : '');
	}

	public static function allowRender($shortcodeData) {
		if (is_admin()) {
			return true;
		}
		$id = $shortcodeData->getId();
		$savedData = $shortcodeData->getData();
		$allowForCurrentDevice = self::allowForCurrentDevice($savedData, $shortcodeData);
		if(!$allowForCurrentDevice) {
			return false;
		}

		return true;
	}

	public static function allowForCurrentDevice($options, $shortcodeData) {
		$status = true;
		if (!empty($options['arm-show-on-selected-devices'])) {
			$devices = $options['arm-selected-devices'];
			$hideContent = $options['arm-hide-content'];
			$currentDevice = AdminHelperPro::getUserDevice();
			if(!in_array($currentDevice, $devices)) {
				if($hideContent) {
					$shortcodeData->setToggleContent('');
				}
				$status = false;
			}
		}

		return $status;
	}
}