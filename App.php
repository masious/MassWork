<?php

/**
* The main usage of this class is to handle
* the components controlling.
*/
abstract class App{

	/**
	 *  needed components
	 */
	var $components = array();
	
	/**
	 * loads the components
	 */
	function __construct(){
		// model needs to be loaded soon! because they might be needed in components
		require_once MODELS_DIR . DS . 'Model.php';
		if(count($this->components) != 0){
			require_once COMPONENTS_DIR . DS . 'Component.php';
			foreach($this->components as $component){
				$this->loadComponent($component,isset($this->$component)?$this->$component:null);
			}
		}
	}
	
	/**
	 * 
	 * @param string $component
	 * @param mixed $arg
	 */
	protected function loadComponent($component,$arg = null){
		require_once COMPONENTS_DIR . DS . $component . '.php';
		if($arg)
			$this->$component = new $component($arg);
		else
			$this->$component = new $component();
	}
	
	/**
	 * this function is called before the action method is called from go method
	 */
	protected function beforeScaffolding(){
		foreach($this->components as $component){
			$this->$component->beforeScaffolding($this);
		}
	}
	
	/**
	 * this function is called before the view is rendered and after the action method is called
	 */
	protected function beforeRender(){
		foreach($this->components as $component){
			$this->$component->beforeRender($this);
		}
	}
	
	/**
	 * this is the function which is called after the view and its layout is rendered
	 */
	protected function afterRender(){
		foreach($this->components as $component){
			$this->$component->afterRender($this);
		}
	}
}