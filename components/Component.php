<?php
abstract class Component extends App{

	var $componentName;
	
	private $loadedComponents = array();
	
	function __construct($arg0 = null){
		parent::__construct();
		if(gettype($arg0) == 'array'){
			foreach($arg0 as $key=>$val)
				if(array_search($key,get_class_vars(get_class($this))) !== false)
					$this->$key = $val;
		}
		
		if(!$this->componentName)
			$this->componentName = get_called_class();
	}
	
	function beforeScaffolding(){}
	
	function beforeRender(){}
	
	function afterRender(){}
	
	// Override
	function loadComponent($component,$arg = null){
		if(array_search($component,$this->loadedComponents) !== false)
			return ;
		
		return parent::loadComponent($component,$arg = null);
	}
	
	protected function log($txt){
		file_append_contents(COMPONENTS_DIR . DS . $this->componentName.'Component.log',$txt.' '.date('Y/m/d H:i:s').'\n');
	}
	
	protected function callMethod($methodName,$args = null){
		if(is_callable($this->$methodName))
			$this->$methodName($args);
	}
}