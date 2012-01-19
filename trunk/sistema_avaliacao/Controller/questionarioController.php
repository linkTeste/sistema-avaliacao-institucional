<?php
if (!isset($_SESSION)) {
	session_start();
}
//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual página chamar de acordo com a action

//incluir aqui as classes que serao usadas
require "../Model/Bean/questionario.class.php";
require "../Model/DAO/questionarioDAO.class.php";

/**
 * @name questionarioController
 * @author Fabio Baía
 * @since 12/01/2012
 * controller do questionario - responsável por tratar as requisições via get, post ou session.
 * Controla o fluxo da aplicação definindo qual página chamar de acordo com a action recebida.
 **/
//class questionarioController {
	$action;
	$page;
	
	$default_page = "home.php";

	$questionario;
	$questionarioDAO;

	
	questionarioController();

	/**
	 * @name questionarioController
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que verifica a action e direciona para a action específica
	 **/
	function questionarioController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}

		if($action == "new"){
			//redireciona para pagina de cadastro
			//echo "aqui eu coloco um avariável na sessão indicando q é pra mostrar o form de cadastro";
			
			//como vou tentar fazer tudo em uma view só a action definira quais partes da view devem ser mostradas
			
			$questionario = new questionario();
			
			prepareSession($questionario, $action);
			$page = "questionarios.php";
			redirectTo($page);
		}
		if($action == "edit"){
			//se for edicao pega o id do questionario que será editado
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
						
			$questionarioDAO = new questionarioDAO();
			$questionario = $questionarioDAO->get($id);
			
			//debug
			//print_r($questionario);
			
			prepareSession($questionario, $action);
			$page = "questionarios.php";
			redirectTo($page);
			
		}
		if($action == "delete"){
			//se for edicao pega o id do questionario que será excluido
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
		
			$questionarioDAO = new questionarioDAO();
			$questionarioDAO->remove($id);
			$questionario = new questionario();
				
			//definir uma mensagem aqui pra enviar pro cliente				
			prepareSession($questionario, $action);
			$page = "questionarios.php";
			redirectTo($page);
				
		}
		if($action == "save"){			
			save();				
		}
	}

	/**
	 * @name save
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função responsavel por cadastrar/atualizar um questionario
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
			
		if(isset($_POST["description"])){
			$descricao = $_POST["description"];
		}
			
		if(isset($_POST["instrumento"])){
			$instrumento_id = $_POST["instrumento"];
		}
			
		$questionario = new questionario();
		$questionario->setId($id);
		$questionario->setDescricao($descricao);
		$questionario->setInstrumento_id($instrumento_id);
			
		$questionarioDAO = new questionarioDAO();
		$status = $questionarioDAO->persiste($questionario);
		$mensagem;	
		if($status = true){
			//cadastrado com sucesso
			//exibir alguma mensagem aqui
			$mensagem = "Cadastrado com Sucesso!";
		}
			
		prepareSession($questionario, $action, $mensagem);
		$page = "questionarios.php";
		redirectTo($page);
	}

	
	/**
	 * @name prepareSession
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que lança dados na sessão
	 **/
	function prepareSession(questionario $questionario, $action, $mensagem = null) {
		//prepara a sessao
		//seta valores na sessao
		//session_start();
		
		$_SESSION["action"] = $action;
		$_SESSION["questionario"] = $questionario;
		$_SESSION["mensagem"] = $mensagem;
		
		}

	/**
	 * @name redirectTo
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que redireciona pra uma pagina específica
	 **/
	function redirectTo($page) {
		$url_base = "http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/";
		header("Location: ".$url_base.$page);
	}


//}


?>
