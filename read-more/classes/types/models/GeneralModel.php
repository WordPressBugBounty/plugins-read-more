<?php
namespace arm;
require_once(dirname(__FILE__).'/ModelAbstract.php');

class GeneralModel extends ModelAbstract {
	const decodeCols = array('options');
	const fields = array('id', 'type','expm-title','button-width','animation-duration', 'options');
	const fieldsFormat = array('id' => '%d', 'type' => '%s','expm-title' => '%s','button-width' => '%s','animation-duration' => '%s', 'options' => '%s');
}