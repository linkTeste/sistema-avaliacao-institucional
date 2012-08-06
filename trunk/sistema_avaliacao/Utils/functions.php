<?php

/**
* @name datetime_to_ptbr
* @author Fabio Ba�a
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
* @name ptbr_to_datetime
* @author Fabio Ba�a
* @since 10/03/2012 21:52:42
* Converte uma string do formato(dd/mm/yy h:m:s) no formato formato(Y/m/d H:i:s)
**/
function ptbr_to_datetime($datetime) {
	$da = strval(substr($datetime,0,2));
	$mo = strval(substr($datetime,3,2));
	$yr = strval(substr($datetime,6,4));
	
	$hr = strval(substr($datetime,11,2));
	$mi = strval(substr($datetime,14,2));
	$sg = strval(substr($datetime,17,2));
	
	return date("Y-m-d H:i:s", mktime ($hr,$mi,$sg,$mo,$da,$yr));
}

/**
* @name date_to_ptbr
* @author Fabio Ba�a
* @since 17/02/2012 16:44:48
* Converte um date do formato(Y/m/d) no formato brasileiro(d/m/Y)
**/
function date_to_ptbr($date) {
	$yr=strval(substr($date,0,4));
	$mo=strval(substr($date,5,2));
	$da=strval(substr($date,8,2));
	
	return date("d/m/Y", mktime (0,0,0,$mo,$da,$yr));
}

/**
* @name isDayOrNight
* @author Fabio Ba�a
* @since 22/05/2012 23:45:13
* retorna uma imagem representando se o horario � diurno ou noturno
**/
function isDayOrNight($datetime) {
	$hr = strval(substr($datetime,11,2));
		
	$ret;
	if($hr > 06 && $hr < 18){
		//dia
		$ret = "css/images/dia.png";
	}else{
		//noite
		$ret = "css/images/noite.png";
	}
	return $ret;
}



/**
* @name pegaImagem
* @author Fabio Ba�a
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

/**
* @name stringToUpper
* @author Fabio Ba�a
* @since 22/05/2012 23:00:57
* transforma uma string em Uppercase preservando os acentos
**/
function stringToUpper($term) {
	$palavra = strtr(strtoupper($term),"������������������������������","������������������������������");
	return $palavra;
}

/**
* @name stringToLower
* @author Fabio Ba�a
* @since 22/05/2012 23:02:04
* transforma uma string em Lowercase preservando os acentos
**/
function stringToLower($term) {
	$palavra = strtr(strtolower($term),"������������������������������","������������������������������");
	return $palavra;
}

/**
* @name codifica
* @author Fabio Baía
* @since 18/06/2012 15:34:10
* codifica uma string usando base64 e comprime ela
**/
function codifica($str) {
	$time = time();
	$urlCodificada = urlencode(base64_encode(gzcompress($time."///".$str)));
		
	return $urlCodificada; 
}

/**
* @name decodifica
* @author Fabio Baía
* @since 18/06/2012 17:44:37
* decodifica uma string de base64 comprimida
**/
function decodifica($str) {
	//descomprime e decodifica
	$page_decodificada = @gzuncompress(base64_decode($str));
	if( !$page_decodificada )
	{
		exit("A URL informada é inválida!");
	}
	
	// 	echo $page_decodificada;
	// 	echo "<br />";
	
	$tokens = explode("///", $page_decodificada);
	return $tokens;
}

/**
* @name decodeParams
* @author Fabio Baía
* @since 18/06/2012 20:53:08
* @return array
* decodifica parametros
**/
function decodeParams($paramString) {
	$tokens = decodifica($paramString);

	// $tokens[0] = time
	// $tokens[1] = string
	$params_temp = explode("&", $tokens[1]);
	$parametros = array();
	
	//percorre o array temporario pra criar os pares chave/valor de parametros
	foreach ($params_temp as $param)
	{
		$temp = explode("=", $param);
		$parametros[$temp[0]] = $temp[1];		
	}
	
	return $parametros;
}

/**
* @name redirectTo
* @author Fabio Baía
* @since 12/01/2012
* função que redireciona pra uma pagina específica
**/
function redirectTo($page) {
	//obs: alterado pra funcionar entre os subsistemas unicampo
// 	$url_base = "http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/";
	$url_base = "http://ca.faculdadeunicampo.edu.br/sistema_avaliacao/View/";
	

	//joga a pagina ativa na sessao e direciona pro controller de paginas
	$_SESSION["s_active_page"] = $page;
	header("Location: ".$url_base."system.php");
}

/*--------------------------------- FUNCTION CHARTS  -------------------------------*/
/**
* @name escalaDecimal
* @author Fabio Baía
* @since 29/06/2012 12:54:23
* converte um valor de base 5 em base 10
**/
function escalaDecimal($param) {
	return ($param*10)/5;
}

/**
* @name notasProfessores
* @author Fabio Baía
* @since 29/06/2012 13:48:12
* insert a description here
**/
// function notasProfessores($param) {
// 	;
// }

/**
* @name discoveryDisciplineName
* @author Fabio Baía
* @since 29/06/2012 14:57:39
* insert a description here
**/
// function discoveryDisciplineName($id) {
// 	;
// }