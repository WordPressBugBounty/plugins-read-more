<?php
namespace arm;

class OptionsConfig {
	public static function optionsValues()
	{
		global $ARM_OPTIONS;
		$options = array();
		$options[] = array('name' => 'yrm-type', 'type' => 'text', 'defaultValue' => 'button');
		$options[] = array('name' => 'button-width', 'type' => 'text', 'defaultValue' => '100px');
		$options[] = array('name' => 'button-height', 'type' => 'text', 'defaultValue' => '32px');
		$options[] = array('name' => 'animation-duration', 'type' => 'text', 'defaultValue' => '1000');
		$options[] = array('name' => 'font-size', 'type' => 'text', 'defaultValue' => '14px');
		$options[] = array('name' => 'show-less-scroll-top', 'type' => 'checkbox', 'defaultValue' => '');

		// PRO
		$options[] = array('name' => 'btn-background-color', 'type' => 'text', 'defaultValue' => '#81d742');
		$options[] = array('name' => 'btn-text-color', 'type' => 'text', 'defaultValue' => '#ffffff');
		$options[] = array('name' => 'btn-border-radius', 'type' => 'text', 'defaultValue' => '20px');
		$options[] = array('name' => 'horizontal', 'type' => 'text', 'defaultValue' => 'center');
		$options[] = array('name' => 'vertical', 'type' => 'text', 'defaultValue' => 'bottom');
		$options[] = array('name' => 'show-only-mobile', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'arm-show-on-selected-devices', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'arm-hide-content', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'arm-selected-devices', 'type' => 'array', 'defaultValue' => '');
		$options[] = array('name' => 'expander-font-family', 'type' => 'text', 'defaultValue' => 'Diplomata SC');
		$options[] = array('name' => 'hover-effect', 'type' => 'checkbox', 'defaultValue' => '');
		$options[] = array('name' => 'btn-hover-text-color', 'type' => 'text', 'defaultValue' => '');
		$options[] = array('name' => 'btn-hover-bg-color', 'type' => 'text', 'defaultValue' => '');

		$ARM_OPTIONS = apply_filters('armDefaultOptions', $options);
		self::types();
		return $ARM_OPTIONS;
	}

	public static function types() {
		global $ARM_TYPES;
		$ARM_TYPES['typePath'] = apply_filters('armTypePaths', array(
			'button' => YRM_TYPES
		));
	}
}