<?php
$hostAcademico="mysql01-farm26.kinghost.net";
$userAcademico="faculdadeunica";
$passAcademico="unicampobd";
$DBAcademico="faculdadeunica";

$conexaoAcademico = mysql_connect($hostAcademico,$userAcademico,$passAcademico, true) or die (mysql_error("impossivel se conectar no sistema academico"));
$bancoAcademico = mysql_select_db($DBAcademico, $conexaoAcademico);

?>