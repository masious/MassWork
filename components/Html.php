<?php
class Html extends Component{
	private $isReady = false;
	private $cssArrs = array();
	private $jsArrs = array();
	
	function beforeRender($arg0){
		$this->isReady = true;
	}
	
	function css($fileName){
		array_push($this->cssArrs,$fileName);
	}
	
	function js($fileName){
		array_push($this->jsArrs,$fileName);
	}
	
	function renderCss(){
		$ret = '';
		foreach($this->cssArrs as $css){
			$ret .= '<link rel="stylesheet" href="' . CSS_URL. '/' .$css.'.css" />';
		}
		return $ret;
	}
	
	function renderJs(){
		$ret = '';
		foreach($this->jsArrs as $js){
			$ret .= '<script src="'.JS_URL . '/' . $js.'.js"></script>';
		}
		return $ret;
	}
}