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

