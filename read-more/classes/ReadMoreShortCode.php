<?php
class ReadMoreShortCode {
	
	public function __construct() {
		add_shortcode('read_more', array($this, 'doShortCode'));
	}

	public function doShortCode($args, $content) {

		if(!isset($args['id'])) {
			return '';
		}

		$id = $args['id'];
		$moreName = @$args['more'];
		$lessName = @$args['less'];

		$dataObj = new ReadMoreData();
		$dataObj->setId($id);
		$savedData = $dataObj->getSavedOptions();

		if(empty($savedData)) {
			return $content;
		}
		$savedData['moreName'] = $moreName;
		$savedData['lessName'] = $lessName;

		$includeManagerObj = new ReadMoreIncludeManager();
		$includeManagerObj->setId($id);
		$includeManagerObj->setData($savedData);
		$includeManagerObj->setToggleContent($content);
		return $includeManagerObj->render();
	}
}