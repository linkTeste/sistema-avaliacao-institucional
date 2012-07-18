<?php
session_start();
require '../Utils/functions.php';

if(isset($_GET["pg"])){
	//recebe o parametro comprimido e codificado
	$pg = $_GET["pg"];
	//debug
// 	echo $pg;
// 	echo "<br />";

	$tokens = decodifica($pg);
	$time = time();
// 	echo "microtime antigo".$tokens[0];
// 	echo "<br />";
// 	echo "microtime atual".$time;
// 	echo "<br />";
	$diferenca = $time-$tokens[0];

	$tempoExpiracao = 60*60; //1h = 60min*60sec

// 	echo "diferença: ".$diferenca;
// 	echo "<br />";
	if($diferenca > $tempoExpiracao){
		//redireciona pra algum lugar - ex: login.php
		echo "O link que você tentou acessar expirou";
	}else{
		//echo "valido";
		//pega a page
		$page = $tokens[1];
		
		$url_base = "http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/";
				
		//joga a pagina ativa na sessao e direciona pra pagina default
		$_SESSION["s_active_page"] = $page;
// 		header("Location: ".$url_base."system.php");
		header("Location: ".$url_base."system.php".$tokens[2]);
		
	}

}else{

}