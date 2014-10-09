<?php
require './core/bootstrap.php';

if(isset($_GET['path']))
	$path = urldecode( $_GET['path'] );
else
	$path = HOME_VIEW;

if(substr($path,-1,1) == '/')
	$path = substr($path,0,strlen($path)-1);

// patterns
$specificDataView = '/^([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)\/(.+)$/'; // paths which are like /controller/view/data
$specificView = '/^([a-zA-Z0-9_]+)\/([a-zA-Z0-9_]+)$/'; // paths with specific view and without specific data like controller/view
$controllerIndex = '/^([a-zA-Z0-9_]+)([\/]|){1}$/'; // paths which just have controller name like /controller
// $componentPages = '/^Components\/([a-zA-Z0-9]+)\/([a-zA-Z0-9]+)$/'; // pages that may be wanted by the components

$isSpecificView = $isSpecificDataView = $isControllerIndex = false;
//matching path with patterns
if(	(strpos($path,'assets/') !== 0) &&
	$isControllerIndex 	=preg_match($controllerIndex,$path,$matches) 	||  	// index view is wanted
	$isSpecificView		=preg_match($specificView,$path,$matches) 		|| 		// a specific view is wanted
	$isSpecificDataView	=preg_match($specificDataView,$path,$matches) 			// a specific view with data is wanted
	){
	
	if($isSpecificDataView){
		$controller = $matches[1];
		$view = $matches[2];
		$data = $matches[3];
	
	} else if($isSpecificView){
		$controller = $matches[1];
		$view = $matches[2];
		$data = null;
	
	} else if($isControllerIndex){
		$controller = $matches[1];
		$view = DEFAULT_VIEW;
		$data = null;
	}
	
	if( $controller == 'Controller' )
		error( 'Controller can\'t be called.' );
		
	require ROOT . DS . 'App.php';
	require CONTROLLER_DIR . DS . 'Controller.php';
	
	if(file_exists( CONTROLLER_DIR . DS . $controller . '.php' ))
		require CONTROLLER_DIR . DS . $controller . '.php';
		
	else if(file_exists( CONTROLLER_DIR . DS . $controller . 'Controller.php' ))
		require CONTROLLER_DIR . DS . $controller . 'Controller.php';
	
	else
		error('Controller '.$controller.' does not exist.');
		
	$contObj = new $controller(new RapnaRequest($controller,$view,$data));
	$contObj->go($view);
	
}
/// I basically disagree using Auth as a component.

 // else if(preg_match($componentPages,$path,$matches)){ // a specific page of a model is wanted
	// $component = $matches[1];
	// $view = $matches[2];
	
	// if( $component == 'Component' )
		// error( 'Component can\'t be called.' );
		
	// require COMPONENT_DIR . DS . 'Component.php';
	
	// if(file_exists( COMPONENT_DIR . DS . $component . '.php' ))
		// require COMPONENT_DIR . DS . $component . '.php';
		
	// else if(file_exists( COMPONENT_DIR . DS . $component . 'Component.php' ))
		// require COMPONENT_DIR . DS . $component . 'Component.php';
	
	// else
		// error('Component '.$component.' does not exist.');
		
	// $compObj = new $component(new RapnaRequest($component,$view,$data));
	// $compObj->go($view);
	
// }
else
	echo file_get_contents(ROOT . DS . str_replace('/', '\\', $path));
