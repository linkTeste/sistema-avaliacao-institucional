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

require_once '../system/application/models/dao/Questionario.php';
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Comentarios.php';

//if (!isset($_SESSION)) {
session_start();
//}

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

	
	avaliacaoController();

	/**
	 * @name avaliacaoController
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que verifica a action e direciona para a action específica
	 **/
	function avaliacaoController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}

		if($action == "avaliar"){
			//redireciona para pagina de avaliacao
						
			//pega a turma
			if(isset($_GET["turma"])){
				$id_turma = $_GET["turma"];
			}
			$turma = new Turma();
			$turma->get($id_turma);
			$questionario_id = $turma->getQuestionarioId();
			
			$questionario = new Questionario();
			$questionario->get($questionario_id);
			
			
			$_SESSION["turma"] = serialize($turma);
			$_SESSION["questionario"] = serialize($questionario);
			
			
			$page = "avaliacao.php";
			redirectTo($page);
		}
		
		//cria um array pra armazenar as questoes com as respectivas notas
		$questoesNotas = array();
		$cont = 0;
		
		if($action == "saveScore"){
			if(isset($_SESSION["questoesNotas"])){
				$questoesNotas = $_SESSION["questoesNotas"];
			}
			
			
			if(isset($_POST["score"])){
				$score = $_POST["score"];
			}
			if(isset($_POST["question_id"])){
				$question_id = $_POST["question_id"];
			}
						
			//echo "The score is: ".$score;
			//echo "<br />";
			//echo "The question: ".$question_id;

			
			
			$questoesNotas[$question_id] = array("question_id" => $question_id,
												 "nota" => $score);
			
			$_SESSION["questoesNotas"] = $questoesNotas;
			
			//zera o array para testes debug
			//$_SESSION["questoesNotas"] = null;
			
			//print_r($questoesNotas);	
			
			echo $question_id;			
			
			
		}
		if($action == "saveInDatabase"){
			if(isset($_POST["questionario_id"])){
				$questionario_id = $_POST["questionario_id"];
			}
			
			//echo "questionario id".$questionario_id;
			
						
			
			
			$questoesNotas = $_SESSION["questoesNotas"];
			$aluno = unserialize($_SESSION["aluno"]);
			//print_r($questoesNotas);
			
			$processo = unserialize($_SESSION["processo"]);
			$turma = unserialize($_SESSION["turma"]);
			
			foreach ($questoesNotas as $qn){
				$q = $qn[question_id];
				$nota = $qn[nota];
				
// 				echo "nota ".$qn[nota];
// 				echo "<br />";
// 				echo "questao ".$qn[question_id];
					
				$avaliacao = new Avaliacao();
				$avaliacao->setProcessoAvaliacaoId($processo->getId());
				$avaliacao->setQuestionarioHasQuestaoQuestionarioId($questionario_id);
				$avaliacao->setQuestionarioHasQuestaoQuestaoId($q);
				$avaliacao->setTurmaIdTurma($turma->getIdTurma());
				$avaliacao->setNota($nota);
				$avaliacao->setDataAvaliacao(date('Y-m-d H:i:s'));
				$avaliacao->setAlunoRa($aluno->getRa());
				
				$avaliacao->save();
// 				$avaliacao->insert();
			
			}
			
			if(isset($_POST["obs"])){
				$comentario_texto = $_POST["obs"];
				
				//seta o comentario no banco de dados
				$comentario = new Comentarios();
				$comentario->setComentario($comentario_texto);
				$comentario->setDataAvaliacao(date('Y-m-d H:i:s'));
				$comentario->setAlunoRa($aluno->getRa());
				$comentario->setTurmaIdTurma($turma->getIdTurma());
					
				// 			echo $aluno->getRa();
				// 			echo $turma->getIdTurma();
				$comentario->save();
				
			}
			
			
			//zera o array de questoes respondidas da sessao
			$_SESSION["questoesNotas"] = null;
			
			$_SESSION["message"] = "Avaliação realizada com sucesso!";
			$page = "avaliacoes.php";
			redirectTo($page);
				
		}
		
		
		
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