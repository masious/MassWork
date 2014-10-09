<?php
class ATimeCalculator extends Component{
	
	var $components = array('SharedContent');
	
	// Override
	function beforeScaffolding($controller){
		$this->SharedContent->unsetVar('time');
		$this->SharedContent->setVar('time',microtime());
	}
}