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

//if (!isset($_SESSION)) {
session_start();
//}

/**
 * @name processoController
 * @author Fabio Ba�a
 * @since 12/01/2012
 * controller do questionario - respons�vel por tratar as requisi��es via get, post ou session.
 * Controla o fluxo da aplica��o definindo qual p�gina chamar de acordo com a action recebida.
 **/
//class processoController {
	$action;
	$page;
	
	$default_page = "home.php";

	//$questionario;
	//$questionarioDAO;

	
	processoController();

	/**
	 * @name processoController
	 * @author Fabio Ba�a
	 * @since 12/01/2012
	 * fun��o que verifica a action e direciona para a action espec�fica
	 **/
	function processoController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}

		if($action == "new"){
						
			$processo = new ProcessoAvaliacao();
						
			$_SESSION["action"] = $action;
			$_SESSION["s_processo"] = serialize($processo);
			
			$page = "processos.php";
			redirectTo($page);
		}
		if($action == "edit"){
			//se for edicao pega o id do questionario que ser� editado
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
						
			$processo = new ProcessoAvaliacao();
			$processo->get($id);
			
						
			$_SESSION["action"] = $action;
			$_SESSION["s_processo"] = serialize($processo);
			
			$page = "processos.php";
			redirectTo($page);
			
		}
		if($action == "delete"){
			//se for edicao pega o id do questionario que ser� excluido
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
			if(isset($_POST["id"])){
				$id = $_POST["id"];
			}
			
			if(isset($_GET["confirm"])){
				$confirm = $_GET["confirm"];
			}
			if(isset($_POST["confirm"])){
				$confirm = $_POST["confirm"];
			}
			
			
			$processo = new ProcessoAvaliacao();
			$processo->get($id);
			
			if($confirm == "true"){
				//depois excluimos o processo
				$processo->delete();
			}else{
				$_SESSION["action"] = $action;
				$_SESSION["s_processo"] = serialize($processo);
			}			
			
			$page = "processos.php";
			redirectTo($page);
				
		}
		if($action == "details"){
			//se for details pega o id do questionario que sera exibido em detalhes na outra pagina
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
		
			$processo = new ProcessoAvaliacao();
			$processo->get($id);
						
			$_SESSION["action"] = $action;
			$_SESSION["processo"] = serialize($processo);
			//$_SESSION["mensagem"] = $mensagem;
			
			//debug
			//$x = $_SESSION["questionario"];
			//echo "id: ".$x->getDescricao();
			
			$page = "processo.php";
			redirectTo($page);		
		}
		if($action == "ativar"){
			//se for "ativar" pega o id do questionario que ativado e desativa os outros
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
		
			$processos = new ProcessoAvaliacao();
			$processos->find();
			
			while ($processos->fetch()) {
				$p = new ProcessoAvaliacao();
				$p->get($processos->id);
				
				if($processos->id == $id){
					$p->setAtivo("Ativo");
				}else{
					$p->setAtivo("Desativado");
				}
				
				$p->save();
			}			
			
				
			$page = "processos.php";
			redirectTo($page);
		}
		if($action == "save"){			
			save();				
		}
		
	}

	/**
	 * @name save
	 * @author Fabio Ba�a
	 * @since 12/01/2012
	 * fun��o responsavel por cadastrar/atualizar um questionario
	 **/
	function save() {
		$id;
		$descricao;
		$instrumento_id;
		
		if(isset($_POST["id"])){
			if($_POST["id"] == ""){
				$id = 0;
			}else{
				$id = $_POST["id"];
			}
		}
			
		if(isset($_POST["descricao"])){
			$descricao = $_POST["descricao"];
		}
		
		if(isset($_POST["input-inicio"])){
			$inicio = $_POST["input-inicio"];
		}
			
		if(isset($_POST["input-fim"])){
			$fim = $_POST["input-fim"];
		}
			
		$processo = new ProcessoAvaliacao();
		$processo->setId($id);
		$processo->setDescricao($descricao);
		
				
		$processo->setInicio(ptbr_to_datetime($inicio));
		$processo->setFim(ptbr_to_datetime($fim));
		$processo->setDataCriacao(date('Y-m-d H:i:s'));
		$processo->save();
		
// 		$questionarioDAO = new questionarioDAO();
// 		$status = $questionarioDAO->persiste($questionario);
// 		$mensagem;	
// 		if($status = true){
// 			//cadastrado com sucesso
// 			//exibir alguma mensagem aqui
// 			$mensagem = "Cadastrado com Sucesso!";
// 		}
			
		$_SESSION["action"] = $action;
		$_SESSION["s_processo"] = serialize($processo);
		$_SESSION["mensagem"] = $mensagem;
		
		
		$page = "processos.php";
		redirectTo($page);
	}
	
		
	/**
	 * @name prepareSession
	 * @author Fabio Ba�a
	 * @since 12/01/2012
	 * fun��o que lan�a dados na sess�o
	 **/
	function prepareSession(questionario $questionario, $action, $mensagem = null) {
		//prepara a sessao
		//seta valores na sessao
		//session_start();
		
		$_SESSION["action"] = $action;
		$_SESSION["questionario"] = $questionario;
		$_SESSION["mensagem"] = $mensagem;
		
		}


//}


?>
