<?php
session_start();
//verifica se o usuario logou em outro sistema (Portal do Aluno, Ambiente Online, Biblioteca, etc)


if(isset($_SESSION[user]) && $_SESSION[user] != "" && isset($_SESSION[senha]) && $_SESSION[senha] != ""){
	//acesso aluno
	$_SESSION["acesso_externo"] = "true";
	header("location: ../Controller/loginController.php");

}else if(isset($_SESSION[usuario]) && $_SESSION[usuario] != "" 
		&& isset($_SESSION[senha]) && $_SESSION[senha] != "" 
		&& isset($_SESSION[nivel_acesso]) && $_SESSION[nivel_acesso] == "professor"){
	//acesso professor
	$_SESSION["acesso_externo"] = "true";
	header("location: ../Controller/loginController.php");

}else{
	echo "Você não esta logado corretamente. Faça login novamente.";
}