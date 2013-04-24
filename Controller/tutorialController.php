<?php

require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require '../Utils/functions.php';

session_start();

/**
 * @name tutorialController
 * @author Fabio Baía
 * @since 18/07/2012 
 **/
	$action;
	$page;
	
	$default_page = "home.php";

	tutorialController();

	/**
	* @name tutorialController
	* @author Fabio Baía
	* @since 27/07/2012 15:42:14
	* insert a description here
	**/
	function tutorialController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}
		
		if(isset($_GET["page"])){
			$page = $_GET["page"];
		}

		if($action == "ativar"){
			if(isset($_GET["tut_id"])){
				$tutId = $_GET["tut_id"];
			}
		
			$_SESSION["s_tutorial"] = $tutId;			
			redirectTo($page);
		}
		if($action == "desativar"){
				
			$_SESSION["s_tutorial"] = false;
			redirectTo($page);
		}
				
	}



?>
