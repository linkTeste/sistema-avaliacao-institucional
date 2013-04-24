<?php

$host = "mysql01-farm26.kinghost.net";
$login_db = "faculdadeunica05";
$senha_db = "avaliacaounicampo159";
$database = "faculdadeunica05";

$conn = mysql_connect("$host","$login_db","$senha_db");
$banco = mysql_select_db("$database");


///////////////////////


//pega o tipo e subtipo da questao pra filtrar as questoes
// $questionario = unserialize($_SESSION["s_questionario"]);
$tipo = utf8_decode($_GET["tipo"]);
$subtipo = utf8_decode($_GET["subtipo"]);
// $tipo = $_GET["tipo"];
// $subtipo = $_GET["subtipo"];


//////////////////////



//$q=strtolower ($_GET["term"]);
$q=utf8_decode($_GET["term"]);
//$q=$_GET["term"];

//echo $_GET["term"];

// $sql = "SELECT * FROM questao WHERE texto like '%$q%'";
$sql = "SELECT * FROM questao WHERE texto like '%$q%' and tipo = '$tipo' and subtipo = '$subtipo'";


$result = mysql_query($sql);// or die ("Erro". mysql_query());

// while($reg=mysql_fetch_array($query)){

// 	//if (srtpos(strtolower($reg['nom_lista']),$q !== false){
// 	echo $reg["texto"]."|".$reg["texto"]."\n";
// 	//	}
// }


//formata o resultado para JSON
$json = '[';
$first = true;
while($row = mysql_fetch_array($result))
{
	if (!$first) {
		$json .= ',';
	} else { $first = false;
	}
	$json .= '{"value":"'.utf8_encode($row['texto']).'"}';

}
$json .= ']';

echo $json;

