<?php
// Class forked from https://wordpress.org/plugins/expand-maker/
class ReadMoreFunctions {

	public static function createSelectBox($params, $name, $selectedValue) {

		$selectBox = "<select name='".$name."' class=\"selectpicker\">";
		foreach ($params as $value => $name) {
			$selected = "";
			if($value == $selectedValue) {
				$selected = "selected";
			}
			$selectBox .= "<option value='".esc_attr($value)."' $selected>$name</option>";
		}
		$selectBox .= "</select>";
		return $selectBox;
	}
	
	public static function yrmSelectBox($data, $selectedValue, $attrs) {
		
		$attrString = '';
		$selected = '';
		
		if(!empty($attrs) && isset($attrs)) {
			
			foreach ($attrs as $attrName => $attrValue) {
				$attrString .= ''.$attrName.'="'.$attrValue.'" ';
			}
		}
		
		$selectBox = '<select '.$attrString.'>';
		
		if (!empty($data)) {
			foreach ($data as $value => $label) {
				
				/*When is multiselect*/
				if(is_array($selectedValue)) {
					$isSelected = in_array($value, $selectedValue);
					if($isSelected) {
						$selected = 'selected';
					}
				}
				else if($selectedValue == $value) {
					$selected = 'selected';
				}
				else if(is_array($value) && in_array($selectedValue, $value)) {
					$selected = 'selected';
				}
				
				$selectBox .= '<option value="'.esc_attr($value).'" '.$selected.'>'.$label.'</option>';
				$selected = '';
			}
		}
		
		$selectBox .= '</select>';
		
		return $selectBox;
	}
}