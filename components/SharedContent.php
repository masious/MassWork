<?php
class SharedContent extends Component{
	
	private $appName;
	private $conn;
	
	function __construct($arg0 = null){
		parent::__construct();
		$this->model = new SharedContentModel();
	}
	
	function isDefined($var){
		return $this->getVar($var)?true:false;
	}
	
	function getVar($var){
		if(isset($this->$var)){
			return $this->$var;
		}
		return $this->$var = $this->model->findBy('var',$var);
	}
	
	function setVar($var , $val = null){
		$data = array(
			'var'=>$var,
			'val'=>$val
		);
		$this->model->save($data);
		return $val;
	}
	
	// if the val is defined, the unset happens only when the val 
	// parameter and the val in the database are the same
	function unsetVar($var,$val = null){
		if($val == null)
			return $this->model->delete(array('var'=>$var));
		else 
			return $this->model->delete(array('var'=>$var,'val'=>$val));
	}
}

class SharedContentModel extends Model{
	var $tableName = 'shared_content';
}