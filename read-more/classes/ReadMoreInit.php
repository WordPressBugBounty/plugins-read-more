<?php
// Class forked from https://wordpress.org/plugins/expand-maker/
class ReadMoreInit {

	private $pagesObj;

	function __construct() {

		if (YRM_PKG != 1) {
			require_once(YRM_FILES.'AdminHelperPro.php');
		}
		require_once(YRM_MODELS.'GeneralModel.php');
		require_once(YRM_TYPES.'ReadMore.php');
		require_once(YRM_FILES.'ReadMoreAdminPost.php');
		require_once(YRM_CLASSES.'ReadMoreInstall.php');
		require_once(YRM_CLASSES.'ReadMoreIncludeManager.php');
		require_once(YRM_CLASSES.'ReadMoreData.php');
		require_once(YRM_CLASSES.'ReadMoreFunctions.php');
		require_once(YRM_CSS.'ReadMoreStyles.php');
		require_once(YRM_JAVASCRIPT_PATH.'javascript.php');
		require_once(YRM_ADMIN.'Actions.php');
		require_once(YRM_FILES.'ReadMoreActions.php');
		require_once(YRM_FILES.'RadMoreAjax.php');
		require_once(YRM_CLASSES.'ReadMorePages.php');

		$pagesObj =  new ReadMorePages();
		$readMoreData = new ReadMoreData();
		$functionsObj = new ReadMoreFunctions();

		$this->pagesObj = $pagesObj;
		$pagesObj->readMoreDataObj = $readMoreData;
		$pagesObj->functionsObj = $functionsObj;
		$this->actions();
	}

	public function activate() {

		ReadMoreInstall::install();
	}

	public function uninstall() {

		ReadMoreInstall::uninstall();
	}

	public function shortCode() {

		require_once(YRM_CLASSES.'ReadMoreShortCode.php');
		$shortCodeObj = new ReadMoreShortCode();
	}

	public function enqueueScripts() {
		
	}

	public function readMoreMainMenu() {
		$typeObj = apply_filters('armPageContent', 'mainMenu');
		$this->pagesObj->mainPage($typeObj);
	}

	public function addNewButton() {
		$typeObj = apply_filters('armPageContent', 'addNewButton');
		$this->pagesObj->addNewButtons($typeObj);
	}

	public function addNewPage() {
		$typeObj = apply_filters('armPageContent', 'addNewPage');
		$this->pagesObj->addNewPage($typeObj);
	}

	public function expmAdminMenu() {
		add_menu_page('Read more', 'Read more', 'manage_options','readMore',array($this, 'readMoreMainMenu'));
		add_submenu_page('readMore','Add New','Add New','manage_options','addNew', array($this,'addNewPage'));
		add_submenu_page('readMore','Add New button','Add New button','manage_options','button', array($this,'addNewButton'));
	}

	public function actions() {
		$actions = new ReadMoreActions();
		add_action('admin_menu', array($this, 'expmAdminMenu'));
		add_action('wp_head',  array($this, 'shortCode'));

		$this->activateActions();

		add_action('admin_post_update_data', array('DataProcessing', 'expanderUpdateData'));
	}

	public function activateActions() {
		add_action('wpmu_new_blog', array($this, 'activate'), 10, 6);
		register_activation_hook(YRM_PATH.YRM_MAIN_FILE,  array($this, 'activate'));
		register_uninstall_hook(YRM_PATH.YRM_MAIN_FILE,  array('ExpMaker', 'uninstall'));
	}
}