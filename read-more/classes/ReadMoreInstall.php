<?php
// Class forked from https://wordpress.org/plugins/expand-maker/
class ReadMoreInstall
{
	public static function createTables($blogId = '') {

		global $wpdb;
		$createTableStr = "CREATE TABLE IF NOT EXISTS ". $wpdb->prefix.$blogId;

		$expanderDataBase = $createTableStr."expm_maker (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`type` varchar(255) NOT NULL,
			`expm-title` varchar(255) NOT NULL,
			`button-width` varchar(255) NOT NULL,
			`button-height` varchar(255) NOT NULL,
			`animation-duration` varchar(255) NOT NULL,
			`options` text NOT NULL,
			PRIMARY KEY (id)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8; ";

		$wpdb->query($expanderDataBase);
	}

	public static function install() {

		self::createTables();
		if(is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			foreach($sites as $site) {
				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				if($blogId != 1) {
					self::createTables($blogId);
				}
			}
		}
	}

	public static function uninstall() {

		$obj = new self();
		$obj->uninstallTables();
		if (is_multisite() && get_current_blog_id() == 1) {
			global $wp_version;
			if($wp_version > '4.6.0') {
				$sites = get_sites();
			}
			else {
				$sites = wp_get_sites();
			}
			foreach($sites as $site) {
				if($wp_version > '4.6.0') {
					$blogId = $site->blog_id."_";
				}
				else {
					$blogId = $site['blog_id']."_";
				}
				$obj->uninstallTables($blogId);
			}
		}
	}

	public function uninstallTables($blogId = '') {

		if(YRM_PKG == 1) {
			return false;
		}
		global $wpdb;
		$expanderTable = $wpdb->prefix.$blogId."expm_maker";
		$expmSql = "DROP TABLE ". $expanderTable;
		$wpdb->query($expmSql);
		return true;
	}
}