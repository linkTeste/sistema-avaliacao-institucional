<?php

/**
* @name datetime_to_ptbr
* @author Fabio Baía
* @since 10/01/2012
* Converte um datetime do formato(Y/m/d H:i:s) no formato brasileiro(d/m/Y H:i:s)
**/
function datetime_to_ptbr($datetime) {
	$yr=strval(substr($datetime,0,4));
	$mo=strval(substr($datetime,5,2));
	$da=strval(substr($datetime,8,2));
	$hr=strval(substr($datetime,11,2));
	$mi=strval(substr($datetime,14,2));

	$sg=strval(substr($datetime,17,2));

	//return date("d/m/Y H:i:s", mktime ($hr,$mi,0,$mo,$da,$yr));
	return date("d/m/Y H:i:s", mktime ($hr,$mi,$sg,$mo,$da,$yr));
}

/**
* @name pegaImagem
* @author Fabio Baía
* @since 15/02/2012 16:54:42
* retorna a url da imagem a ser exibida ou retorna a url da imagem padrao 
**/
function pegaImagem($id) {
	//pega a imagem do ID pelo id ou usa uma imagem padrao
	//usar o @ para suprimir o warning
	$handle = @fopen("css/images/avatar/".$id.".jpg", "r");
	
		
	if($handle == false){
		//nao achou a imagem, entao usa a padrao
		$img = "css/images/avatar/default.png";
	}else{
		//achou a imagem, entao usa ela
		$img = "css/images/avatar/".$id.".jpg";
	}
	return $img;
}
