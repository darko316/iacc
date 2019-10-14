<?php

function encriptar($string,$md5=false){		
		$string = $md5 ? $string : md5($string);
		$encrypted = password_hash ($string,PASSWORD_DEFAULT );
		return $encrypted;
}	

function validarClave($stringEncriptado,$hash){		
		return password_verify($stringEncriptado, $hash);
}

?>