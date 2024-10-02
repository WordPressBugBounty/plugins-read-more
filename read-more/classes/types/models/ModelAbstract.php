<?php
namespace arm;
global $wpdb;

abstract class ModelAbstract {

	const TABLE = 'expm_maker';
	const decodeCols = array();
	private $ar_dirties = array();

	public static function getTableName() {
		global $wpdb;
		return $wpdb->prefix.static::TABLE;
	}

	public function __call($name, $args) {
		$methodPrefix = substr($name, 0, 3);
		$methodProperty = lcfirst(substr($name, 3));

		if ($methodPrefix == 'get') {
			return $this->$methodProperty;
		}
		else if ($methodPrefix == 'set') {

			if (!empty($args[0])) {
				$value = $args[0];
				if (!in_array($methodProperty, $this->ar_dirties)) $this->ar_dirties[] = $methodProperty;
				$this->$methodProperty = $value;
			}
		}
	}

	public static function findByID($id) {
		$criteria = ' WHERE id=%d';
		$data = self::find($criteria, array($id));

		return $data;
	}

	public static function find($criteria='', $params=array()) {
		$query = 'SELECT * FROM '.self::getTableName();

		global $wpdb;
		$prepareQuery = $wpdb->prepare($query.$criteria, implode(',', $params));
		$resData = $wpdb->get_row($prepareQuery);

		return self::fetchObject($resData);
	}

	public static function fetchObject($data) {
		if ($data && count($data)) {
			return self::instantiate($data);
		}

		return new static();
	}

	/**
	 *
	 * Create a new instance of the current class
	 *
	 * @param array $data  data to assign to the new created instance
	 * @return object of current class
	 */
	private static function instantiate($data) {
		$obj = new static();

		return self::addDataToObj($obj, $data);
	}

	private static function filterName($name) {
		return str_replace('-', '_', $name);
	}

	private static function resetName($name) {
		return str_replace('_', '-', $name);
	}

	public static function addDataToObj($obj, $data) {
		foreach ($data as $key=>$val) {
			$keyName = self::filterName($key);
			if (in_array($keyName, static::decodeCols)) {
				$decodeVal = json_decode($val, true);
				self::addDataToObj($obj, $decodeVal);
			}
			$obj->$keyName = $val;
		}

		return $obj;
	}

	public function save() {
		global $wpdb;
		$id = $this->getId();

		$values = array();
		$keyNames = array();
		$sanitizeKeys = array();
		$table = static::getTableName();

		foreach ($this->ar_dirties as $name) {
			$filteredName = self::resetName($name);
			if (!isset($this->$name)) {
				continue;
			}
			$keyNames[] = $filteredName;
			$values[$filteredName] = $this->$name;
			$sanitizeKey = '%s';
			if (!empty(static::fieldsFormat[$name])) {
				$sanitizeKey = static::fieldsFormat[$name];
			}
			$sanitizeKeys[] = $sanitizeKey;
		}

		if (empty($id)) {
			$wpdb->insert($table, $values, $sanitizeKeys);
		}
		else {
			$where = array('id' => $id);
			$where_format = array('%d');
			$wpdb->update($table, $values, $where, $sanitizeKeys, $where_format);
		}
	}
}