<?php

/**
 *  for ending a request with an error
 *  @var string $str - the text of the error message
 *  @var string shouldDie - specifies if the run should interrupt or not
 */
function error( $str ,$shouldDie = true){
	echo '<pre>';
	var_dump(debug_backtrace());
	echo '</pre>';
	
	if($shouldDie)
		die( $str );
	else
		echo $str;
}

/**
 * for adding string at the end of a file
 * @param string $file
 * @param string $contents
 * @return number
 */
function file_append_contents($file,$contents){
	return file_put_contents($file, $contents, FILE_APPEND | LOCK_EX);
}

/**
 * 
 * @param string CamelCasedName
 * @return under_scored_name
 */
function cc2ul($name){
	return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));
}

/**
 * 
 * @param string $name - the name of the variable
 * @param string $first_char_caps - if the first char should be uppercase or not
 * @return mixed
 */
function ul2cc($string , $first_char_caps = false){
    if($first_char_caps)
        $string[0] = strtoupper($string[0]);
    for($i=1;$i<strlen($string)-1;$i++){
    	if($string[$i] == '_')
    		$string[$i+1] = strtoupper($string[$i+1]);
    }
    return preg_replace('/([_]+)/', '', $string);
}
