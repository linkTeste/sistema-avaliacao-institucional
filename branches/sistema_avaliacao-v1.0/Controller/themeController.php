<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual p�gina chamar de acordo com a action

//incluir aqui as classes que serao usadas
//require "../Model/Bean/questionario.class.php";
//require "../Model/DAO/questionarioDAO.class.php";

require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require '../Utils/functions.php';

session_start();

/**
 * @name themeController
 * @author Fabio Baía
 * @since 18/07/2012 
 **/
	$action;
	$page;
	
	$default_page = "home.php";

	//$questionario;
	//$questionarioDAO;

	
	themeController();

	/**
	* @name themeControler
	* @author Fabio Baía
	* @since 18/07/2012 17:13:09
	* insert a description here
	**/
	function themeController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}

		if($action == "ativar"){
			//se for "ativar" pega o nome do thema que será ativado e desativa os outros
			if(isset($_GET["theme"])){
				$theme = $_GET["theme"];
			}
		
			$_SESSION["s_theme"] = $theme;		
				
			$page = "configuracoes.php";
			redirectTo($page);
		}
				
	}



?>
