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

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/QuestionarioUsado.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';

//if (!isset($_SESSION)) {
session_start();
//}

/**
 * @name questionarioController
 * @author Fabio Ba�a
 * @since 12/01/2012
 * controller do questionario - respons�vel por tratar as requisi��es via get, post ou session.
 * Controla o fluxo da aplica��o definindo qual p�gina chamar de acordo com a action recebida.
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
 * @author Fabio Ba�a
 * @since 12/01/2012
 * fun��o que verifica a action e direciona para a action espec�fica
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
		//echo "aqui eu coloco um avari�vel na sess�o indicando q � pra mostrar o form de cadastro";
			
		//como vou tentar fazer tudo em uma view s� a action definira quais partes da view devem ser mostradas
			
		$questionario = new questionario();
			
		prepareSession($questionario, $action);
		$page = "questionarios.php";
		redirectTo($page);
	}
	if($action == "edit"){
		//se for edicao pega o id do questionario que ser� editado
		if(isset($_GET["id"])){
			$id = $_GET["id"];
		}

		$questionario = new Questionario();
		$questionario->get($id);
			

		$_SESSION["action"] = $action;
		$_SESSION["questionario"] = serialize($questionario);
			
		$page = "questionarios.php";
		redirectTo($page);
			
	}
	if($action == "delete"){
		//se for edicao pega o id do questionario que ser� excluido
		if(isset($_GET["id"])){
			$id = $_GET["id"];
		}

		$questionario = new Questionario();
		$questionario->get($id);
			
		//primeiro devemos remover as questoes do questionario
			
		$questionario->alias('q');
		$q = new Questao();
		$questionario->join($q,'INNER','qu');
		$questionario->select("qu.id, qu.texto, qu.topico");
		$questionario->find();

		$pos = 0;
		$ids_remover = array();
		while( $questionario->fetch() ) {
			$ids_remover[$pos] = $questionario->id;
			$pos++;
		}
			
		//criamos aqui um questionario2 pq o questionario agora guarda os
		//valores das questoes e por isso nao e mais um questionario
			
		$questionario2 = new Questionario();
		$questionario2->get($id);
		$questionario2->remove('questoes', $ids_remover);
			
		//depois excluimos o questionario
		$questionario2->delete();
			

		//session
			
		$page = "questionarios.php";
		redirectTo($page);

	}
	if($action == "details"){
		//se for details pega o id do questionario que sera exibido em detalhes na outra pagina
		if(isset($_GET["id"])){
			$id = $_GET["id"];
		}

		$questionario = new Questionario();
		$questionario->get($id);

		$_SESSION["action"] = $action;
		$_SESSION["s_questionario"] = serialize($questionario);
		//$_SESSION["mensagem"] = $mensagem;
			
		//debug
		//$x = $_SESSION["questionario"];
		//echo "id: ".$x->getDescricao();
			
		$page = "questionario.php";
		redirectTo($page);
	}
	if($action == "definirQuestionario"){
		definirQuestionarios();
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
		
	if(isset($_POST["description"])){
		$descricao = utf8_decode($_POST["description"]);
	}
		
	if(isset($_POST["instrumento"])){
		$instrumento_id = $_POST["instrumento"];
	}

	if(isset($_POST["tipo"])){
		$tipo = utf8_decode($_POST["tipo"]);
	}

	if(isset($_POST["subtipo"])){
		$subtipo = utf8_decode($_POST["subtipo"]);
	}
	
	
		
	$questionario = new Questionario();
	$questionario->setId($id);
	$questionario->setDescricao($descricao);

	$questionario->setTipo($tipo);
	$questionario->setSubtipo($subtipo);

	// 		$questionario->setInstrumentoId($instrumento_id);

	$questionario->setDataCreate(date('Y-m-d H:i:s'));
	$questionario->save();

	// 		$questionarioDAO = new questionarioDAO();
	// 		$status = $questionarioDAO->persiste($questionario);
	// 		$mensagem;
	// 		if($status = true){
	// 			//cadastrado com sucesso
	// 			//exibir alguma mensagem aqui
	// 			$mensagem = "Cadastrado com Sucesso!";
	// 		}
		
	prepareSession($questionario, $action, $mensagem);
	$page = "questionarios.php";
	redirectTo($page);
}


/**
 * @name definirQuestionarios
 * @author Fabio Ba�a
 * @since 09/03/2012 13:22:21
 * insert a description here
 **/
function definirQuestionarios() {
	$arrayTipo = array();
	$arraySubtipo = array();
	$arrayQuest = array();

	$arrayTSQ = array();
	$size;
	$id;
	$processo;
	
	if(isset($_SESSION["s_processo"])){
		$str = $_SESSION["s_processo"];
		if($str instanceof ProcessoAvaliacao){
			$processo = $str;
		}else{
			$processo = unserialize($_SESSION["s_processo"]);
		}
		// 	echo $processo->getId();
		// 	exit;
	}

	if(isset($_POST["id"])){
		if($_POST["id"] == ""){
			$id = 0;
		}else{
			$id = $_POST["id"];
		}
	}

	if(isset($_POST['tipo'])){

		foreach($_POST['tipo'] as $keyTipo)
		{
			$arrayTipo[] = $keyTipo;
			//echo "Tipo: ".$keyTipo;
		}
		//echo "<br />";

		$size = count($arrayTipo);

		foreach($_POST['subtipo'] as $keySubtipo)
		{
			$arraySubtipo[] = $keySubtipo;
// 			$arraySubtipo[] = utf8_decode($keySubtipo);
			//echo "Subtipo: ".$keySubtipo;
		}
		//echo "<br />";

		foreach($_POST['quest'] as $keyQuest)
		{
			$arrayQuest[] = $keyQuest;
			//echo "quest: ".$keyQuest;
		}
		
// 		debug
// 		print_r($_POST['tipo']);
// 		echo "<br />";
// 		echo $_POST['subtipo'];
// 		exit;

		
		for ($i = 0; $i < $size; $i++) {
			$arrayTSQ[$arrayTipo[$i]] = $arrayQuest[$i];

			$questionarioUsado = new QuestionarioUsado();
			
			$questionarioUsado->tipo = $arrayTipo[$i];
			$questionarioUsado->subtipo = $arraySubtipo[$i];
			
			//pegar o processo atual
			//$questionarioUsado = new QuestionarioUsado();
			$questionarioUsado->processoAvaliacaoId = $processo->getId();
			
// 			echo "processo >> ".$processo->getId();
// 			exit;
			//$questionarioUsado->processoAvaliacaoId = 1;
			
			$questionarioUsado->find(true);
			
			$questionarioUsado->setQuestionarioId($arrayQuest[$i]);
			$questionarioUsado->save();
			


		}
		
		
		//definir a msg de sucesso e jogar na sessao
		//$_SESSION["questionario"] = serialize($questionario);
			
		$page = "questionarios.php";
		redirectTo($page);

		//print_r($arrayTurmasQuest);

	}
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

/**
 * @name redirectTo
 * @author Fabio Ba�a
 * @since 12/01/2012
 * fun��o que redireciona pra uma pagina espec�fica
 **/
function redirectTo($page) {
	$url_base = "http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/";
	header("Location: ".$url_base.$page);
}


//}


?>
