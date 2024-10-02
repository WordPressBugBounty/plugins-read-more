<?php
// Class forked from https://wordpress.org/plugins/expand-maker/
if(!class_exists('YrmConfig')) {

	class YrmConfig {

		public function __construct() {

			$this->init();
		}

		public static function addDefine($name, $value) {
			if(!defined($name)) {
				define($name, $value);
			}
		}

		public function getDirectoryName() {

			$baseName = plugin_basename(__FILE__);
			$readMoreDirectoryName = explode('/', $baseName);

			if(isset($readMoreDirectoryName[0])) {
				return $readMoreDirectoryName[0];
			}

			return '';
		}

		private function init() {

			$pluginKey = $this->getDirectoryName();

			// General Defines
			self::addDefine('YRM_PLUGIN_PREFIX', $pluginKey);
			self::addDefine('YRM_MAIN_FILE', $pluginKey.'.php');
			self::addDefine('YRM_PATH', WP_PLUGIN_DIR.'/'.$pluginKey.'/');
			self::addDefine('YRM_CLASSES', YRM_PATH.'classes/');
			self::addDefine('YRM_TYPES', YRM_CLASSES.'types/');
			self::addDefine('YRM_ADMIN', YRM_CLASSES.'admin/');
			self::addDefine('YRM_MODELS', YRM_CLASSES.'models/');
			self::addDefine('YRM_FILES', YRM_PATH.'files/');

			self::addDefine('YRM_URL', WP_PLUGIN_URL.'/'.$pluginKey.'/');

			// public
			self::addDefine('YRM_JAVASCRIPT', YRM_URL.'js/');
			self::addDefine('YRM_ADMIN_JAVASCRIPT', YRM_JAVASCRIPT.'admin/');
			self::addDefine('YRM_CSS_URL', YRM_URL.'css/');
			self::addDefine('YRM_ADMIN_CSS_URL', YRM_CSS_URL.'admin/');
			self::addDefine('YRM_CSS', YRM_PATH.'css/');
			self::addDefine('YRM_VIEWS', YRM_PATH.'views/');
			self::addDefine('YRM_JAVASCRIPT_PATH', YRM_PATH.'js/');

			self::addDefine('YRM_TEXT_DOMAIN', 'yrm_lang');

			// versions
			self::addDefine('YRM_VERSION', 1.12);
			self::addDefine('YRM_PRO_VERSION', 1.07);

			require_once(dirname(__FILE__).'/config-pkg.php');

			self::addDefine('YRM_PRO_URL', 'https://edmonsoft.com/read-more/');
		}
	}

	$configInit = new YrmConfig();
}