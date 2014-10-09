<?php

class Session extends Component{
	
	/**
	 * 
	 * @var string - the name of the controller which uses this component
	 */
	var $controllerName;
	
	/**
	 * @var string - the name of the variable which this component uses to store the flash message 
	 */
	private static $flashName = 'sessionflash';
	
	function __construct(){
		session_start();
	}
	
	/**
	 * shows all the session vars which are set before.
	 * @return string
	 */
	function report(){
		ob_start();
			echo '<pre>';
			var_dump($_SESSION);
			echo '</pre>';
		return ob_get_clean();
	}
	
	/**
	 * this method puts an extra string in the begining of the var name 
	 * which is used for generating private session vars 
	 * @param string $name
	 * @return string
	 */
	private function getPrivateVarName($bareName){
		return $this->controllerName.'.'.$bareName;
	}
	
	/**
	 * checks whether if a var is private or not
	 * @param string $var
	 * @return boolean
	 */
	private function _isPrivate($var){
		if(isset($_SESSION[$this->getPrivateVarName($var)]))
			return true;
		return false;
	}
	
	/**
	 * sets a session variable with the given value
	 * @param string $mixed
	 * @param mixed $val
	 * @param boolean $isPrivate
	 */
	function set($var,$val,$isPrivate = false){
		if( $isPrivate )
			$_SESSION[$this->getPrivateVarName($var)] = $val;
		else
			$_SESSION[$var] = $val;
	}
	
	/**
	 * for getting the value of a set session var
	 * @param string $var
	 * @return mixed|NULL
	 */
	function get($var){
		if(isset($_SESSION[$var]))
			return $_SESSION[$var];
		else if(isset($_SESSION[$this->controllerName.'.'.$var]))
			return $_SESSION[$this->controllerName.'.'.$var];
		else{
			$this->log($var.' is not set.');
			return null;
		}
	}
	
	/**
	 * checks whether a variable is set or not
	 * @param string $var
	 */
	function isVarSet($var){
		return isset($_SESSION[$var]);
	}
	
	/**
	 * for unsetting the value of an already set variable in the session
	 * @param string $var
	 * @return boolean
	 */
	function unsetVar($var){
		if(isset($_SESSION[$var]))
			unset($_SESSION[$var]);
		else if(isset($_SESSION[$this->controllerName.'.'.$var]))
			unset($_SESSION[$this->controllerName.'.'.$var]);
		else{
			$this->log($var.' and '.$this->controllerName.'.'.$var.' are not set.');
			return false;
		}
	}
	
	/**
	 * unsetting the session vars and unsetting the given SESSID
	 * @return boolean
	 */
	function kill(){
		unset($_SESSION);
		return session_destroy();
	}
	
	function beforeScaffolding($controller){
		$name = $controller->request->controller;
		
		if($name == 'Session'){
			$this->log('Controller\'s name can not be "Session"');
			return ;
		}
		$this->controllerName = $name;
	}
	
	/**
	 * this method is used a message to the user.
	 * the layout renders the value of flash every time it renders
	 */
	function setFlash($data){
		return $this->set(Session::$flashName,$data);
	}
	
	/**
	 * this method is used in the layout to get the message in the flash
	 * note that everytime it is used, it's flushed
	 * @return mixed
	 */
	function getFlash(){
		$ret = $this->get(Session::$flashName);
		$this->unsetVar(Session::$flashName);
		return $ret;
	}
	
	//private function internalSet($var,$val){
	//	return 
}