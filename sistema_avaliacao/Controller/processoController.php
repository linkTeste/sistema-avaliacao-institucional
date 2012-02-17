<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual página chamar de acordo com a action

//incluir aqui as classes que serao usadas
//require "../Model/Bean/questionario.class.php";
//require "../Model/DAO/questionarioDAO.class.php";

require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/ProcessoAvaliacao.php';

//if (!isset($_SESSION)) {
session_start();
//}

/**
 * @name processoController
 * @author Fabio Baía
 * @since 12/01/2012
 * controller do questionario - responsável por tratar as requisições via get, post ou session.
 * Controla o fluxo da aplicação definindo qual página chamar de acordo com a action recebida.
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
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que verifica a action e direciona para a action específica
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
			$_SESSION["processo"] = serialize($processo);
			
			$page = "processos.php";
			redirectTo($page);
		}
		if($action == "edit"){
			//se for edicao pega o id do questionario que será editado
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
						
			$processo = new ProcessoAvaliacao();
			$processo->get($id);
			
						
			$_SESSION["action"] = $action;
			$_SESSION["processo"] = serialize($processo);
			
			$page = "processos.php";
			redirectTo($page);
			
		}
		if($action == "delete"){
			//se for edicao pega o id do questionario que será excluido
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
		
			$processo = new ProcessoAvaliacao();
			$processo->get($id);
			
			//primeiro devemos remover as questoes do questionario
			
// 			$questionario->alias('q');
// 			$q = new Questao();
// 			$questionario->join($q,'INNER','qu');
// 			$questionario->select("qu.id, qu.texto, qu.topico");
// 			$questionario->find();
			 
// 			$pos = 0;
// 			$ids_remover = array();
// 			while( $questionario->fetch() ) {
// 				$ids_remover[$pos] = $questionario->id;
// 				$pos++;			
// 			}
			
			//criamos aqui um questionario2 pq o questionario agora guarda os 
			//valores das questoes e por isso nao e mais um questionario
			
// 			$questionario2 = new Questionario();
// 			$questionario2->get($id);
// 			$questionario2->remove('questoes', $ids_remover);
			
			//depois excluimos o processo	
			$processo->delete();
			
				
			//session
			
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
		$processo->setInicio($inicio);
		$processo->setFim($fim);
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
		$_SESSION["processo"] = $processo;
		$_SESSION["mensagem"] = $mensagem;
		
		
		$page = "processos.php";
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
