<?php


class FileHandler extends Component{
	
	private static $idHolder = './FileHandler.php_';
	private static $base = 100;
	private static $defaultExtension = '';// MUST have '.' character
	
	function getRoot(){
		return ASSETS_DIR . DS . 'gallery';
	}
	
	function write($str){
		$id = $this->getLastId();
		$id++;
		$dir = $this->getRoot();
		if( !file_exists($dir) )
			mkdir($dir);

		$dir .= DS . FileHandler::getBase($id);
		if( !file_exists($dir) )
			mkdir($dir);

		file_put_contents( $this->getRoot() . DS . $this->getBase($id) . DS . $id . self::$defaultExtension , $str);
		$this->idPP();
		return $id++;
	}
	
	function read($id){
		return file_get_contents( $this->getRoot() . DS . $this->getBase($id) . DS . $id . $this->defaultExtension );
	}
	
	private function getLastId(){
		return (int) file_get_contents(COMPONENTS_DIR . DS . self::$idHolder);
	}
	
	function getBase($id){
		return (int) ($id/self::$base);
	}
	
	private function idPP(){
		$id = $this->getLastId();
		$id++;
		return file_put_contents(COMPONENTS_DIR . DS . self::$idHolder,$id);
	}
	
}