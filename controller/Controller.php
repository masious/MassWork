<?php
abstract class Controller extends App{

	/**
	 * the Rapna request goes here
	 * @var RapnaRequest
	 */ 
	var $request;
	
	/**
	 * the view that is going to be rendered
	 * $var string
	 */
	var $render = null;
	
	/**
	 * should the view be rendered or not
	 * @var boolean
	 */ 
	var $autoRender = true;
	
	/**
	 *  the vars which are needed in the view
	 *  @var array
	 */
	private $viewVars = array();
	
	/**
	 *  the name of the view which will be rendered finally
	 *  @var string
	 */
	private $view = null;
	
	/**
	 * this property becomes true after the page is rendered.
	 * @var boolean
	 */
	private $isRendered = false;
	
	/**
	 * the layout which wraps the view and the entire content
	 * @var string
	 */
	var $layout = DEFAULT_LAYOUT;
	
	/**
	 * needed components
	 * @var string 
	 */
	var $components = array();
	
	/**
	 * used model
	 * @var mixed - can be string or array
	 */
	var $uses = array();
	
	/**
	 * Constructor
	 * 
	 * @param RapnaRequest $request object for this controller
	 */
	final function __construct($request){
		parent::__construct();
		$this->controllerName = $request->controller;
		$this->request = $request;
		
		if( gettype($this->uses) == 'array'){
			foreach($this->uses as $m){
				$this->loadModel($m);
			}
		} else if( gettype($this->uses) == 'string'){
			$this->loadModel($this->uses);
		}
	}
	
	/**
	 * loads the model names which are set in the $uses variable
	 * @param String $modelName
	 * @return null
	 */
	protected final function loadModel($modelName){
		require_once MODELS_DIR . DS . $modelName . '.php';
		$this->$modelName = new $modelName();
	}
	
	/**
	 * Sets the variables to be used in the views
	 * @param String $var - The name of the variable which will be used in the view
	 * @param mixed $val - The value of the variable
	 */
	final protected function set($var,$val){
		array_push($this->viewVars,array($var=>$val));
	}
	
	/**
	 * This method is called from the dispatcher, to handle the request
	 * and render the view
	 * @param String $view
	 */
	final function go($view){
		$this->view = $view;
		//before scaffolding
		$this->beforeScaffolding();
		
		//scaffolding
		$this->$view($this->request->data);
		
		if($this->autoRender && !$this->isRendered){
			//before rendering
			$this->beforeRender();
						
			//rendering the view
			$this->render($this->view);

			//after rendering
			$this->afterRender();
		}
	}
	
	/**
	 * Redirects the user to another page
	 * @param String $url
	 */
	final function redirect($url){
		header('Location: '. URLBASE . '/'. $url);
	}
	
	/**
	 * Renders the view and wraps the layout around
	 * @param String $view
	 */
	final protected function render($view = null){
		if(!$this->autoRender || $this->isRendered)
			return ;
			
		$this->isRendered = true;
		$this->render = (!$view)?$this->view:$view;
		
		$this->render = cc2ul($this->render);
		
		extract($this->viewVars);
		foreach($this->viewVars as $vars){
			extract($vars);
		}
		
		$file = VIEWS_DIR . DS . $this->controllerName . DS . $this->render . '.php';
		if(!file_exists($file)){
			echo 'file "'.$file.'" does not exist. please make sure you\'ve created it.';
			return ;
		}
		ob_start();
			require ( $file );
		$content = ob_get_clean();

		if($this->layout == false)
			echo $content;
		else 
			require LAYOUTS_DIR . DS . $this->layout.'.php';
	}
	
	/**
	 * renders an element
	 * @param string $name
	 * @param array $varsArr
	 * @return string
	 */
	final private function element($___name,$__varsArr = array()){
		if(isset($__varsArr[0]) && gettype($__varsArr[0]) == 'array')
			foreach($__varsArr as $var)
				extract($var);
		else
			extract($__varsArr);
		ob_start();
			require (VIEWS_DIR . DS . 'elements' . DS . $___name . '.php');
		return ob_get_clean();
	}
}