<?php
require '../lumine/Lumine.php';
require '../lumine-conf.php';

//inicializa a configuracao
$cfg = new Lumine_Configuration( $lumineConfig );

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Questao.php';

//if (!isset($_SESSION)) {
session_start();
//}

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual página chamar de acordo com a action

//incluir aqui as classes que serao usadas


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



questaoController();

/**
 * @name questaoController
 * @author Fabio Baía
 * @since 19/01/2012 16:31:56
 * função que verifica a action e direciona para a action específica
 **/
function questaoController() {
	
	//fazer o tratamento aqui da codificacao utf-8, iso, etc
	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}

	if(isset($_GET["action"])){
		$action = $_GET["action"];
	}

	if($action == "new"){
		//pega o id do questionario para adicionar a questao
		if(isset($_GET["questionario_id"])){
			$questionario_id = $_GET["questionario_id"];
		}
			
		
		$questao = new Questao();
		$questionario = new Questionario();
		$questionario->get($questionario_id);
		
		//prepareSession($questionario, $questao, $action);
		$_SESSION["action"] = $action;
		$_SESSION["questionario"] = serialize($questionario);
		$_SESSION["questao"] = serialize($questao);
		$_SESSION["mensagem"] = $mensagem;
		
		$page = "questionario.php";
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
			
		prepareSession($questionario, $questao, $action);
		$page = "questionario.php";
		redirectTo($page);
			
	}
	if($action == "delete"){
		//se for edicao pega o id do questionario que será excluido
		if(isset($_GET["id"])){
			$id = $_GET["id"];
		}
		if(isset($_GET["questionario_id"])){
			$questionario_id = $_GET["questionario_id"];
		}

		$questao = new Questao();
		$questao->get($id);
		
		$questionario = new Questionario();
		$questionario->get($questionario_id);
		
		// muda o alias
    	$questionario->alias('q');
    	// telefone
    	$q = new Questao();
    	// une as classes
    	$questionario->join($q,'INNER','qu');
    	// seleciona os dados desejados
    	$questionario->select("qu.id, qu.texto, qu.topico");
    	// recupera os registros
    	$questionario->find();
    	
    	$id_remover;
    	while( $questionario->fetch() ) {
    		if($questao->getId() == $questionario->getId()){
    			//debug
//     			echo "igual: ".$questao->getId()." - ".$questionario->id;
//     			echo "<br />";
    			$id_remover = $questao->getId();   			
    			
    		}else{
    			//debug
//     			echo "diferente: ".$questao->getId()." - ".$questionario->id;
//     			echo "<br />";   			
    			
    		}
    		
    	}
    	
    	$questionario2 = new Questionario();
    	$questionario2->get($questionario_id);
    	//echo "id a remover ".$id_remover;
    	
    	//exibir mensagem informando a questao removida
    	$questionario2->remove('questoes', array($id_remover));
    	
    	
    	//$questionario->re
    	
		$_SESSION["questionario"] = serialize($questionario2);
		
		$page = "questionario.php";
		redirectTo($page);

	}
	if($action == "save"){
		save();
	}
}


/**
 * @name save
 * @author Fabio Baía
 * @since 19/01/2012 16:33:03
 * função responsavel por cadastrar/atualizar uma questao
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
		
	if(isset($_POST["texto"])){
		$texto = $_POST["texto"];
	}
		
	if(isset($_POST["questionario_id"])){
		$questionario_id = $_POST["questionario_id"];
	}
	
	
	
	
	
	
	$questao = new Questao();
	$questao->texto = utf8_decode($texto);
	
	if(isset($_POST["checkbox-opcional"])){
		$opcional = "opcional";
		$questao->opcional = $opcional;
	}
	else  {
		//$opcional = "";
	}
	
	
	$questao->find(true);
// 	try {
// // 		$questao->texto = $texto;
// // 		$questao->find(true);
		
// 	} catch (Exception $e) {
		
// 	}
	//$questao->setTexto($texto);
	$questao->save();
	
	$questionario = new Questionario();
	$questionario->get($questionario_id);
			
	$array_questions = $questionario->getQuestoes();
	array_push($array_questions, $questao);
	
	$questionario->setQuestoes($array_questions);
	$questionario->update();
	
	$_SESSION["action"] = $action;
	$_SESSION["questionario"] = serialize($questionario);
	$_SESSION["questao"] = serialize($questao);
	$_SESSION["mensagem"] = $mensagem;
		
	//prepareSession($questionario, $questao, $action, $mensagem);
	$page = "questionario.php";
	redirectTo($page);
}



/**
 * @name prepareSession
 * @author Fabio Baía
 * @since 19/01/2012 16:33:35
 * função que lança dados na sessão
 **/
function prepareSession(questionario $questionario, questao $questao, $action, $mensagem = null) {
	//prepara a sessao
	//seta valores na sessao
	//session_start();

	$_SESSION["action"] = $action;
	$_SESSION["questionario"] = $questionario;
	$_SESSION["questao"] = $questao;
	$_SESSION["mensagem"] = $mensagem;

}


/**
 * @name redirectTo
 * @author Fabio Baía
 * @since 19/01/2012 16:34:01
 * função que redireciona pra uma pagina específica
 **/
function redirectTo($page) {
	$url_base = "http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/";
	header("Location: ".$url_base.$page);
}


//}


?>
