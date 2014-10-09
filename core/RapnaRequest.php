<?php

class RapnaRequest{
	var $data,$get,$post,$files,$server,$here;
	
	function __construct($controller,$view,$data = null){
		if( $data != null ){
			$datas = explode('/',$data);
			if(count($datas) > 1){
				foreach($datas as $d){
					$pairs = explode(':',$d);
					if(count($pairs) == 1){
						$this->data = $pairs[0];
					} else {
						$this->get[$pairs[0]] = $pairs[1];
					}
				}
			} else {
				$this->data = $datas[0];
			}
		} else {
			$this->data = array();
		}
		
		$this->controller = $controller;
		$this->view = $view;
		$this->post = $_POST;
		$this->server = $_SERVER;
		$this->files = $_FILES;
		
		$this->here = $this->controller.'/'.$this->view.((gettype($this->data)=='string')?('/'.$this->data):'');
	}
	
	
	// is method post or get
	function is($data){
		return (strcasecmp($this->server['REQUEST_METHOD'],$data) === 0);
	}
}