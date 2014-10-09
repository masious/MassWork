<?php
abstract class Model{

	/**
	 * fields of the model and their restrictions
	 * @var string
	 */
	protected $validation = array();
	
	/**
	 * the name of the table in mysql table that will be interacted 
	 * @var string
	 */
	protected $tableName;
	
	/**
	 * if there is a primary key in the table of the model, it's name, goes here
	 * @var unknown
	 */
	protected $primaryKey = null;
	
	/**
	 * if there's an error after communicating query, the message will go here.
	 */
	public $sqlError;
	
// 	protected $hasOne;
	
// 	protected $belongsTo;
	
// 	protected $hasMany;
	
// 	protected $hasAndBelongsToMany;
	
	function __construct(){
		require_once ROOT . DS . 'core/Sql.php';
		$this->setFields();
	}
	
	/**
	 * 
	 * @param string $field - the name of the field which we want to use for search
	 * @param string $val = null
	 * @param string $neededFields - the fields that will be selected
	 * @return array <int, <string:fieldName,string:value> >
	 */
	public function findBy($field,$val = null,$neededFields='*'){
		return $this->find(array($field=>$val),$neededFields);
	}
	
	/**
	 * sets the fields var after getting their list from table
	 */
	private function setFields(){
		if(isset($this->fields))
			return ;

		$sql = new Sql('getfields',$this->tableName);
		if($sql->hasError)
			error($sql->error);

		$this->fields = array();
		for($i=0;$i<count($sql->answers);$i++)
			$this->fields[$sql->answers[$i]] = $sql->answers[$i];
		
		$this->primaryKey = isset($sql->primaryKey)?$sql->primaryKey:false;
	}
	
	/**
	 *  saves a new record
	 *  @var array $data
	 */
	function save($data){
		$this->validateKeys($data);
		$this->validateData($data);
		$sql = new Sql("INSERT INTO `".$this->tableName."` (`".implode('`,`',array_keys($data))."`) VALUES ('".implode("','",$data)."')");
		$this->sqlError = ($sql->hasError)?$sql->error:false;
		return $sql->hasError?false:$sql->insertId;
	}
	
	/**
	 *  makes the array keys valid to be used in the query
	 *  casts the arr keys from camelCase to under_score_case
	 *   and puts an underline in its beginning
	 */
	private function validateKeys(&$arr){
		$arrDup = $arr;
		foreach($arr as $key=>$val){
			$newKey = $this->validateKey($key);
			if($newKey == $key) continue;
			
			$arrDup[$newKey] = $val;
			unset($arrDup[$key]);
		}
		$arr = $arrDup;
	}
	
	private function validateKey($keyStr){
		if(array_search($keyStr,$this->fields)===false)
			return '_'.cc2ul($keyStr);
		else
			return $keyStr;
	}
	
	public function find($whereArr, $fields = '*') {
		$whereClause = '1=1';
		
		if (gettype ( $fields ) == 'array')
			$fields = implode ( ',', $fields );
		
		if (gettype ( $whereArr ) == 'array') {
			$this->validateKeys ( $whereArr );
			$this->validateData ( $whereArr );
			$whereClause = '';
			$isFirst = true;
			foreach ( $whereArr as $k => $v ) {
				$whereClause .= ($isFirst ? '' : ' AND ') . '`' . $k . '`=\'' . $v . '\'';
			}
		}
		
		$q = 'SELECT ' . $fields . ' FROM `' . $this->tableName . '` WHERE ' . $whereClause;
		$sql = new Sql ( $q );
		if ($sql->hasError)
			error ( $sql->error );

		return count($sql->answers)?$sql->answers:false;
	}
	
	static function underlineTrimmer($name){
		return ($name[0] == '_')?substr($name, 1):$name;
	}
	
	/**
	 *  add slashes to the data values
	 */
	private function validateData(&$arr){
		foreach($arr as &$v){
			$v = addslashes($v);
		}
	}
	
	/**
	 * updates an already inserted row<br>
	 * Note: <b>must</b> have primary key
	 */
	function update($data){
		$this->validateKeys($data);
		$this->validateData($data);
		if(!isset($data[$this->primaryKey]))
			throw new Exception("\$data['$this->primaryKey'] is not set.");
		
		$q = 'UPDATE `'.$this->tableName.'` SET ';
		foreach($data as $key=>$val){
			$q .= '`'.$key.'`'.'=\''.$val.'\',';
		}
		$q = substr($q,0,strlen($q)-1).' ';
		if($this->primaryKey==false)
			return ;
		
		$q .= 'WHERE `'.$this->primaryKey.'`=\''.$data[$this->primaryKey].'\'';
		$sql = new Sql($q);
		$this->sqlError = ($sql->hasError)?$sql->error:false;
		return !$sql->hasError;
	}
	
	function query($queryString){
		return new Sql($queryString);
	}
	
	/**
	 * 
	 * @param array $data - the properties of the record which will be deleted
	 * @return boolean - true if successful and false if not
	 */
	function delete($data){
		$q = "DELETE FROM `".$this->tableName."` WHERE ";
		$this->validateData($data);
		$this->validateKeys($data);
		foreach($data as $key=>$val){
			$q .= $key."='".$val."' , ";
		}
		$q = substr($q,0,-2);
		//echo $q;
		$sql = new Sql($q);
		if($sql->hasError)
			return false;
		return true;
	}
	
	/**
	 * <b>important</b>: using this method, needs primary key to be on "one" column.
	 * @param array $data
	 * @param array $data2
	 */
	function saveOrUpdate($data,$data2=null){
		if($data2 == null) // TODO change needed here
			return;
		
		if(!$this->update($data))
			$this->save($data);
	}
}