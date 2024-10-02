<?php
namespace arm\admin;
use arm\OptionsConfig;
use arm\ReadMore;
use arm\GeneralModel;

class Actions {
	public function __construct()
	{
		$this->init();
	}

	public function init() {
		add_action('armPageContent', array($this, 'pageContent'),1,1);
	}

	public function pageContent($menuName) {

		OptionsConfig::optionsValues();
		$obj = ReadMore::createObj();
		$id = $obj->getId();
		$savedObj = GeneralModel::findByID($id);
		$obj->setSavedObj($savedObj);

		return $obj;
	}
}

new Actions();