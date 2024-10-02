<?php
use \arm\ReadMore;
use \arm\OptionsConfig;

Class ReadMoreAdminPost {

	public function __construct() {

		$this->actions();
	}

	public function actions() {

		add_action('admin_post_save_data', array($this, 'expmSaveData'));
		add_action('admin_post_arm_save_data', array($this, 'save'));
		add_action('admin_post_delete_readmore', array($this, 'expmDeleteData'));
	}

	public function save() {
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('read_more_save');
		}
		$postData = apply_filters('armSavedData', $_POST);
		if(empty($postData)) {
			return false;
		}
		if (!empty($postData['read-more-type'])) {
			OptionsConfig::optionsValues();
			$type = $postData['read-more-type'];

			$typePath = ReadMore::getTypePathFormType($type);
			$className = ReadMore::getTypeClassName($type);

			require_once($typePath.$className.'.php');
			$className = 'arm\\'.$className;
			$className::create($postData);
		}
		global $wpdb;
		$readMoreId = $wpdb->insert_id;
		if (!empty($_POST['read-more-id'])) {
			$readMoreId = (int)$_POST['read-more-id'];
		}
		wp_redirect(admin_url()."admin.php?page=button&readMoreId=".$readMoreId."&type=".$type."&saved=1");
	}

	public function expmSanitizeData($optionName) {

		if(isset($_POST[$optionName])) {
			return sanitize_text_field($_POST[$optionName]);
		}
		return '';
	}

	public function expmDeleteData() {

		global $wpdb;
		$id = (int)$_GET['readMoreId'];
		$wpdb->delete($wpdb->prefix.'expm_maker', array('id'=>$id), array('%d'));
		wp_redirect(admin_url()."admin.php?page=ExpMaker");
	}

	public function expmSaveData() {

		global $wpdb;
		if(isset($_POST)) {
			// Check for CSRF
			check_admin_referer('read_more_save');
		}
		$options = array(
			'font-size'=> $this->expmSanitizeData('font-size'),
			'show-less-scroll-top'=> $this->expmSanitizeData('show-less-scroll-top'),
		);

		if(YRM_PKG > 1) {
			$options['btn-background-color'] = $this->expmSanitizeData('btn-background-color');
			$options['btn-text-color'] = $this->expmSanitizeData('btn-text-color');
			$options['btn-border-radius'] = $this->expmSanitizeData('btn-border-radius');
			$options['horizontal'] = $this->expmSanitizeData('horizontal');
			$options['vertical'] = $this->expmSanitizeData('vertical');
			$options['expander-font-family'] = $this->expmSanitizeData('expander-font-family');
			$options['show-only-mobile'] = $this->expmSanitizeData('show-only-mobile');
			$options['hover-effect'] = $this->expmSanitizeData('hover-effect');
			$options['btn-hover-text-color'] = $this->expmSanitizeData('btn-hover-text-color');
			$options['btn-hover-bg-color'] = $this->expmSanitizeData('btn-hover-bg-color');
		}

		$options = json_encode($options);
		$id = $this->expmSanitizeData('read-more-id');
		$title = $this->expmSanitizeData('expm-title');
		$type = $this->expmSanitizeData('read-more-type');
		$width = $this->expmSanitizeData('button-width');
		$height = $this->expmSanitizeData('button-height');
		$duration = $this->expmSanitizeData('animation-duration');
	
		$data = array(
			'type' => $type,
			'expm-title' => $title,
			'button-width' => $width,
			'button-height' => $height,
			'animation-duration' => $duration,
			'options' => $options
		);

		$format = array(
			'%s',
			'%s',
			'%s',
			'%s',
			'%s',
		);
		if(!$id) {
			$wpdb->insert($wpdb->prefix.'expm_maker', $data, $format);
			$readMoreId = $wpdb->insert_id;
		}
		else {
			$data['id'] = $id;
			$wpdb->update($wpdb->prefix.'expm_maker', $data, array('id'=>$id), $format, array('%d'));
			$readMoreId = $id;
		}
		
		wp_redirect(admin_url()."admin.php?page=button&readMoreId=".$readMoreId."&type=".$type."&saved=1");
	}

}

$readMoreAdminPost = new ReadMoreAdminPost();