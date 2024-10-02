<?php
/**
 * Plugin Name: Read More by Adam
 * Description: With the read more plugin you can show or hide your hidden content
 * Version: 1.1.8
 * Author: Adam Skaat
 * Author URI: 
 * License: GPLv2
 */

if(!defined('ABSPATH')) {
	exit();
}

require_once(dirname(__FILE__).'/config/boot.php');
require_once(YRM_CLASSES.'ReadMoreInit.php');

$readMoreObj = new ReadMoreInit();