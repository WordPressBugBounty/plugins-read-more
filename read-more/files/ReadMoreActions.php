<?php
// Class forked from https://wordpress.org/plugins/expand-maker/

CLass ReadMoreActions {

	public function __construct() {

		$ajaxObj = new RadMoreAjax();
		$ajaxObj->init();
		add_action('admin_enqueue_scripts', array(new ReadMoreStyles(),'registerStyles'));
		add_action('wp_head', array($this, 'readMoreWpHead'));
	}

	public function readMoreWpHead() {

		echo '<script>readMoreArgs = []</script>';
	}
}