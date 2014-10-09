<?php

class Sql{

	static $queries = array();
	var $connection,$queryString,$answerNumber,$answers,$answer,$primaryKey,$hasError = false;
	
	static function getLatestID($table,$column){
		$t = new Sql("SELECT $column FROM $table ORDER BY projectID DESC LIMIT 1");
		return $t->answer[$column];
	}
	
	private function _connect(){
		$connection = mysql_connect(DB_ADDRESS,DB_USERNAME,DB_PASSWORD) or die(mysql_error());
		mysql_select_db(DB_NAME) or die(mysql_error());
		mysql_query("SET CHARACTER SET utf8;");
		mysql_query("SET SESSION collation_connection = 'utf8_general_ci'");
	}
	
	private function _query( $queryString ){
		$this->queryString = $queryString;
		//for reporting
		array_push(Sql::$queries,$queryString);
		
		$query = mysql_query($queryString) or ($this->hasError = true);
		if($this->hasError){
			$this->errorNum = mysql_errno();
            $this->error = mysql_error().': '.$queryString;
			return ;
		}
		if( gettype($query) == "resource" )
			$this->answerNumber = mysql_num_rows($query);
		return $query;
	}
	
	private function _selectQuery( $queryString ){
	
		$query = $this->_query($queryString);
        if(!$query)
            return ;

		for($i=0;$ans=mysql_fetch_assoc($query);$i++){
			$this->answers[$i] = $ans;
		}
		if(mysql_num_rows($query) == 1)
			$this->answer = $this->answers[0];
	}
	
	private function _updateQuery( $queryString ){
		return $this->_query($queryString);
	}
	
	private function _deleteQuery( $queryString ){
		return $this->_query($queryString);
	}
	
	//second syntax: "INSERT INTO tbl_name" with arr: "($key=>value,$key=>$value,...)"
	private function _insertQuery( $queryString , $arr = null){
		if(gettype($arr) == 'array'){
			foreach($arr as $f=>$val){
				$fields[] = $f;
				$vals[] = $val;
			}
			$queryString .= " (`".implode($fields,"`,`")."`) VALUES ('".implode($vals,"','")."')";
		}
		$ret = $this->_query($queryString);
		$this->insertId = mysql_insert_id();
		return $ret;
	}
	
	function _fieldsOf($tbl){
		$r = $this->_selectQuery("SHOW COLUMNS FROM $tbl");
		$ret = array();
		$this->types = array();
		if(gettype($this->answers) == 'array'){
			foreach($this->answers as $res){
				$ret   [] = $res['Field'];
				$this->types [] = $res['Type'];
				
				if($res['Key'] == 'PRI')
					$this->primaryKey = $res['Field'];
			}
		}
		$this->answers = $ret;
		return $ret;
	}
	
	function __construct( $queryString = null , $arg1 = null){
		$w = split(" ",$queryString);
		$type = $w[0];
		$type = strtolower($type);
		$this->_connect();
		switch($type){
		case "select":
			return $this->_selectQuery($queryString);
			break;
		case "update":
			return $this->_updateQuery($queryString);
			break;
		case "delete":
			return $this->_deleteQuery($queryString);
			break;
		case "insert":
			return $this->_insertQuery($queryString,$arg1);
			break;
		case "getfields":// new Sql("getfields","tblname");
			$tbl = $arg1;
			return $this->_fieldsOf($tbl);
			break;
		}
	}
}