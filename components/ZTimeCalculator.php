<?php
class ZTimeCalculator extends Component{
	
	var $components = array('SharedContent');
	var $show = false;
	
	// Override
	function afterRender($controller){
		echo ($this->show)?('token time: '. (microtime() - $this->SharedContent->getVar('time')[0]['val']) .' seconds'):'';
	}
}