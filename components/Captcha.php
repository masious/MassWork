<?php
class Captcha extends Component{

	private $salt = '&!R%^MASioUS~`';
	
	private $size = array('width'=>150,'height'=>35);
	private $lineNumbers = 5;
	private $length = 6;
	private $bgColor = array('R'=>255,'G'=>255,'B'=>255);
	private $textColor = array('R'=>0,'G'=>0,'B'=>0);
	private $font = 'aqua.ttf';
	
	function __construct($inits = array()){
		parent::__construct();
		
		//initializing
		foreach($inits as $k=>$v)
			if($this->$k) // checking that if the var is defined or its fake
				$this->$k = $v;
	}
	
	function makeCaptcha(){
		$hash = $this->hashMaker($_SERVER['REMOTE_ADDR']);
		if(!session_id()){
			session_start();
		}
		$_SESSION['Captcha.hash'] = $hash;
		
		$out = '<img alt="My Image" src="data:image/png;base64,';
		$img = imagecreatetruecolor($this->size['width'],$this->size['height']);
		imageantialias($img,true); // for smoothing the pixels
		imagefilledrectangle($img,0,0,$this->size['width'],$this->size['height'],Captcha::createColor($img,$this->bgColor));
		imagettftext($img,25,0,10,30,Captcha::createColor($img,$this->textColor),ASSETS_DIR.DS.'fonts'.DS.$this->font,$hash);
		
		for($i=0;$i<$this->lineNumbers;$i++){
			$lineColor = /*Captcha::createColor($img,$this->bgColor)*/imagecolorallocate($img,rand(50,150),rand(50,150),rand(50,150));
			imageline($img,rand(0,70),rand(0,$this->size['height']),rand(80,$this->size['width']),rand(0,$this->size['height']),$lineColor);
		}
		
		
		ob_start();
		imagepng($img,null,9);
		$out .= base64_encode(ob_get_clean()).'" />';
		return $out;
	}
	
	private function hashMaker($ID){
		$hash = '';
		for($i=0;$i<$this->length;$i++){
			$hash .= chr((( $a=rand(ord('A'),ord('z')-6) )>90)?$a+6:$a);
		}
		return $hash;
	}
	
	function checkCaptcha($arg0){
		if(stristr($arg0,$_SESSION['Captcha.hash']))
			return true;
		return false;
	}
	
	private static function createColor($src,$arr){
		return imagecolorallocate($src,$arr['R'],$arr['G'],$arr['B']);
	}
}