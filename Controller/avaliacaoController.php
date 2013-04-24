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
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/TurmaHasAluno.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Funcionario.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Comentarios.php';

require '../Utils/functions.php';

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

// 	$questionario;
// 	$questionarioDAO;


avaliacaoController();

/**
 * @name avaliacaoController
 * @author Fabio Ba�a
 * @since 12/01/2012
 * fun��o que verifica a action e direciona para a action espec�fica
 **/
function avaliacaoController() {
	//decodifica o q veio via GET
	if(isset($_GET["p"])){
		$_GET = decodeParams($_GET["p"]);
	}
	
	
	//descobrir aqui o tipo do usuarioLogado
	$userType = $_SESSION["s_usuario_logado_type"];
	
	//fazer o tratamento aqui da codificacao utf-8, iso, etc
	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}

	if(isset($_GET["action"])){
		$action = $_GET["action"];
	}

	if($action == "avaliar"){
		//redireciona para pagina de avaliacao

		//pega o tipo e subtipo da avaliacao
		if(isset($_GET["tipo"])){
			$tipo = $_GET["tipo"];
		}
		if(isset($_GET["subtipo"])){
			$subtipo = $_GET["subtipo"];
		}
		
							
		if($subtipo == "Professor/Disciplina"){
			//pega a turma
			if(isset($_GET["turma"])){
				$id_turma = $_GET["turma"];
			}
			$turma = new Turma();
			$turma->get($id_turma);

			$_SESSION["s_turma"] = serialize($turma);
		}
			
		if($subtipo == "Curso/Coordenador"){
			//pega o curso
			if(isset($_GET["curso"])){
				$curso = $_GET["curso"];
			}

			if(isset($_GET["coordenador_id"])){
				$id_coordenador = $_GET["coordenador_id"];
			}

			//pega o professor
			$professor = new Professor();
			$professor->get($id_coordenador);
			// 				$turma = new Turma();
			// 				$turma->get($id_turma);
				
			$_SESSION["s_curso"] = $curso;
			$_SESSION["s_coordenador"] = serialize($professor);
		}
		
		if($subtipo == "Coordenador"){
// 			if(isset($_GET["curso"])){
// 				$curso = $_GET["curso"];
// 			}
			
			if(isset($_GET["coordenador_id"])){
				$id_coordenador = $_GET["coordenador_id"];
			}
			
			//pega o professor
			$professor = new Professor();
			$professor->get($id_coordenador);
			
			//$_SESSION["s_curso"] = $curso;
			$_SESSION["s_coordenador"] = serialize($professor);
		
		}
			
		if($subtipo == "Instituição"){
			// 				if(isset($_GET["turma"])){
			// 					$id_turma = $_GET["turma"];
			// 				}
			// 				$turma = new Turma();
			// 				$turma->get($id_turma);
				
			// 				$_SESSION["s_turma"] = serialize($turma);

		}
			
		if($subtipo == "Docente"){
			if(isset($_GET["docenteId"])){
				$id_docente = $_GET["docenteId"];
				$docente = new Professor();
				$docente->get($id_docente);
					
				$_SESSION["s_docente"] = serialize($docente);
			}
		}

		$processo = unserialize($_SESSION["s_processo"]);
		
		
		//$questionario_id = $turma->getQuestionarioId();

		//pegamos o questionario id agora da tabela questionario_usado
		$questionarioUsado = new QuestionarioUsado();
		$questionarioUsado->processoAvaliacaoId = $processo->getId();
		//$questionarioUsado->processoAvaliacaoId = 1;
		$questionarioUsado->tipo = $tipo;
		$questionarioUsado->subtipo = $subtipo;
		
		
		$qtd = $questionarioUsado->find(true);
		

		$questionario_id = $questionarioUsado->getQuestionarioId();
		$questionario = new Questionario();
		$questionario->get($questionario_id);
					
		$_SESSION["s_questionario"] = serialize($questionario);
		$_SESSION["tipo"] = $tipo;
		$_SESSION["subtipo"] = $subtipo;

		// 			}
			
	
// 		if($tipo == "Coordenador"){
// 			$page = "avaliacaoCoordenador.php";
// 		}
// 		if($tipo == "Professor"){
// 			$page = "avaliacaoProfessor.php";
// 		}
// 		if($tipo == "Funcionário"){
// 			$page = "avaliacaoFuncionario.php";
// 		}
// 		if($tipo == "Aluno"){
// 			$page = "avaliacao.php";
// 		}

		if($userType == "Coordenador"){
			$page = "avaliacaoCoordenador.php";
		}
		if($userType == "Professor"){
			$page = "avaliacaoProfessor.php";
		}
		if($userType == "Funcionario"){
			$page = "avaliacaoFuncionario.php";
		}
		if($userType == "Aluno"){
			$page = "avaliacao.php";
		}
		
		
			
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
			
		//pega o tipo e subtipo da avaliacao
		if(isset($_POST["tipo"])){
			$tipo = $_POST["tipo"];
		}
		if(isset($_POST["subtipo"])){
			$subtipo = $_POST["subtipo"];
		}
			
		if($tipo == "Coordenador"){
			$usuarioLogado = unserialize($_SESSION["s_usuario_logado"]);

			if($subtipo == "Instituição"){
				$item_avaliado = "Instituição";
			}
			if($subtipo == "Docente"){
				$str = $_SESSION["s_docente"];
				if($str instanceof Professor){
					$temp_docente = $str;
				}else{
					$temp_docente = unserialize($_SESSION["s_docente"]);
				}
				$item_avaliado = $temp_docente->getId();


			}
			if($subtipo == "Auto-avaliação-coordenador"){
				$item_avaliado = $usuarioLogado->getId();
			}

			$avaliador = $usuarioLogado->getId();

		}
		
		if($tipo == "Professor"){
			$usuarioLogado = unserialize($_SESSION["s_usuario_logado"]);
		
			if($subtipo == "Instituição"){
				$item_avaliado = "Instituição";
			}
 			if($subtipo == "Coordenador"){
				$str = $_SESSION["s_coordenador"];
				if($str instanceof Professor){
					$temp_coordenador = $str;
				}else{
					$temp_coordenador = unserialize($_SESSION["s_coordenador"]);
				}
				$item_avaliado = $temp_coordenador->getId();
				
 						
 			}
			if($subtipo == "Auto-avaliação-professor"){
				$item_avaliado = $usuarioLogado->getId();
			}
			if(substr($subtipo, 0, 4) == "Lab_"){
				$item_avaliado = utf8_decode($subtipo);
			}
		
			$avaliador = $usuarioLogado->getId();
		
		}
		
		if($tipo == "Funcionário"){
			$usuarioLogado = unserialize($_SESSION["s_usuario_logado"]);
							
			$avaliador = $usuarioLogado->getId();
		
		}
			
		if($tipo == "Aluno"){	
			$aluno = unserialize($_SESSION["s_aluno"]);
			$avaliador = $aluno->getRa();
			
			if($subtipo == "Professor/Disciplina"){
				$turma = unserialize($_SESSION["s_turma"]);
				$item_avaliado = $turma->getIdTurma();
			}
				
			if($subtipo == "Curso/Coordenador"){
				$curso = $_SESSION["s_curso"];
				$item_avaliado = utf8_decode($curso);
			}
			
			if(substr($subtipo, 0, 4) == "Lab_"){
				$item_avaliado = utf8_decode($subtipo);
			}
			
		}	
		if($subtipo == "Instituição"){
			$item_avaliado = "Instituição";
		}
		if($subtipo == "Sistema"){
			$item_avaliado = "Sistema";
		}
			
			
			

			
			
		$questoesNotas = $_SESSION["questoesNotas"];
		//$aluno = unserialize($_SESSION["s_aluno"]);
		//print_r($questoesNotas);
			
		$processo = unserialize($_SESSION["s_processo"]);
		// 			$turma = unserialize($_SESSION["s_turma"]);


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

			//define o avaliador da avaliacao
			$avaliacao->setAvaliador($avaliador);

			//define o tipo da avaliacao(aluno, professor, cooordenador)
			//tipo avaliador
			$avaliacao->setTipoAvaliacao($tipo);
			
			$avaliacao->setSubtipoAvaliacao($subtipo);

			//define o objeto da avalia��o
			$avaliacao->setItemAvaliado($item_avaliado);

			//substituido pelo objeto da avaliacao
			//$avaliacao->setTurmaIdTurma($turma->getIdTurma());

			$avaliacao->setNota($nota);
			$avaliacao->setDataAvaliacao(date('Y-m-d H:i:s'));

			//substituido pelo avaliador
			//$avaliacao->setAlunoRa($aluno->getRa());

			$avaliacao->save();

			//marca o questionario como avaliado
			//para que ele n�o seja excluido por alguem
			$questionario = new Questionario();
			$questionario->get($questionario_id);
			$questionario->setAvaliado("Avaliado");
			$questionario->save();

			//se for a avaliacao da turma, marca ela
			if($subtipo == "Professor/Disciplina"){
				//marca a turma como avaliada
				$tha = new TurmaHasAluno();
				$tha->turmaIdTurma = $turma->getIdTurma();
				$tha->alunoRa = $avaliador;	
				
				$tha->find(true);
				$tha->setAvaliado("Avaliado");
				$tha->save();
			}


			//marca o processo de avaliacao como avaliado
			//para que ele n�o seja excluido por alguem
			//$processo = new ProcessoAvaliacao();
			$processo->setAvaliado("Avaliado");
			$processo->save();

				
		}
			
		if(isset($_POST["obs"]) && $_POST["obs"] != ""){
			if($subtipo == "Professor/Disciplina"){
				$turma = unserialize($_SESSION["s_turma"]);
				$item_avaliado = $turma->getIdTurma();
			}
				
			if($subtipo == "Curso/Coordenador"){
				$curso = $_SESSION["s_curso"];
				$item_avaliado = $curso;
			}
				
			if($subtipo == "Instituição"){
				$item_avaliado = "Instituição";
			}



			$comentario_texto = $_POST["obs"];

			//seta o comentario no banco de dados
			$comentario = new Comentarios();
			$comentario->setComentario($comentario_texto);
			$comentario->setDataAvaliacao(date('Y-m-d H:i:s'));

			//define o avaliador
			$comentario->setAvaliador($avaliador);

			//define o tipo da avaliacao(aluno, professor, cooordenador)
			//tipo avaliador
			$comentario->setTipoAvaliacao($tipo);
			
			$comentario->setSubtipoAvaliacao($subtipo);

			//define o objeto da avalia��o
			$comentario->setItemAvaliado($item_avaliado);

			$comentario->save();

		}
		
			
			
		//zera o array de questoes respondidas da sessao
		$_SESSION["questoesNotas"] = null;
			
		$_SESSION["s_message"] = "Avaliação realizada com sucesso!";

		//arrumar isso aqui depois
// 		if($usuarioLogado->getIsCoordenador() == 1){
// 			$page = "avaliacoesCoordenador.php";
// 		}
// 		if($usuarioLogado->getIsCoordenador() == 0){
// 			$page = "avaliacoesProfessor.php";
// 		}
		
		
		
// 		if($tipo == "Coordenador"){
// 			$page = "avaliacoesCoordenador.php";
// 		}
// 		if($tipo == "Professor"){
// 			$page = "avaliacoesProfessor.php";
// 		}
// 		if($tipo == "Aluno"){
// 			$page = "avaliacoes.php";
// 		}

		if($userType == "Coordenador"){
			$page = "avaliacoesCoordenador.php";
		}
		if($userType == "Professor"){
			$page = "avaliacoesProfessor.php";
		}
		if($userType == "Funcionario"){
			$page = "avaliacoesFuncionario.php";
		}
		if($userType == "Aluno"){
			$page = "avaliacoes.php";
		}
		
		
			
		redirectTo($page);

	}



}

/**
 * @name getAvaliacoes
 * @author Fabio Ba�a
 * @since 23/02/2012 12:23:00
 * fun��o nao utilizada ate agora
 **/
function getAvaliacoes($ra) {
		
	$aluno = new Aluno();
	$aluno->get($ra);

	$aluno->getTurmas();
		
	// 		$aluno->alias('a');
		
	// 		$t = new Turma();
	// 		$av = new Avaliacao();
	// 		// une as classes
	// 		$aluno->join($t,'INNER','t');
		
	// 		$aluno->join($av, 'INNER', 'av');
	// 		$aluno->select("t.idTurma, t.nomeDisciplina, t.professorId");
	// 		$aluno->where("t.periodoLetivo = '".$periodo_atual."' and t.idTurma not in(SELECT av.turmaIdTurma FROM avaliacao av)");
		
	// 		$aluno->groupBy("t.idTurma");

	// 		// recupera os registros
	// 		$aluno->find();

	while( $aluno->fetch() ) {
		//pega o id do professor

		// 			$id_professor = $aluno->professor_id;

		// 			//pega o professor
		// 			$professor = new Professor();
		// 			$professor->get($id_professor);
		echo $aluno->getRa();

			
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




//}


?>