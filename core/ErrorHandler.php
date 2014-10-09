<?php

//error_reporting(0);


//register_shutdown_function('rapnaErrorFunction');

function rapnaErrorFunction(){
	$err = error_get_last();
	
	throw new RapnaError($err);
}

class RapnaError extends Exception{
	function __construct($err){
		error('An Exception is thrown:<br/><b>'.$err['message'].'</b> in '.$err['file'].': '.$err['line'].'<br/>',false);
	}
}