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
require_once '../system/application/models/dao/Questao.php';
require_once '../system/application/models/dao/Turma.php';
require_once '../system/application/models/dao/Aluno.php';
require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Comentarios.php';

//if (!isset($_SESSION)) {
session_start();
//}

/**
 * @name loginController
 * @author Fabio Ba�a
 * @since 22/02/2012 20:47
 * controller do login - respons�vel por fazer a autentica��o do usuario, seja ele aluno,
 * professor, funcionario ou coordenador.
 **/
//class questionarioController {
$action;
$page;

$default_page = "home.php";


loginController();

/**
 * @name loginController
 * @author Fabio Ba�a
 * @since 22/02/2012 20:48:53
 * fun��o que verifica a action e direciona para a action espec�fica
 **/
function loginController() {

	//fazer o tratamento aqui da codificacao utf-8, iso, etc
	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}

	if(isset($_GET["action"])){
		$action = $_GET["action"];
	}

	if($action == "logar"){

		//primeiro zera a sessao por seguran�a
		//logout();

		//pega os dados e verifica quem esta fazendo o login

		//pega o usuario e a senha
		if(isset($_POST["usuario"])){
			$login = $_POST["usuario"];
		}
			
		if(isset($_POST["senha"])){
			$senha = $_POST["senha"];
		}
			


		//verifica qual o tipo do usuario
		$usuarioLogado = isAluno($login, $senha);
		if($usuarioLogado != false){

			// 			echo "Aluno";
			// 			exit;

			$_SESSION["s_aluno"] = serialize($usuarioLogado);
				
			$page = "index.php";

		}else {
			$usuarioLogado = isUsuario($login, $senha);

			if($usuarioLogado != false){

				// 				echo "Usuario";
				// 				exit;
					
				$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);

				$page = "usuarios.php";
					
			}else{
				$usuarioLogado = isProfessor($login, $senha);
				if($usuarioLogado != false){

					$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);
					
					$page = "avaliacoesteste.php";
						
				}
			}
		}

		// 		if(tipo == "aluno"){
		// 			//se for aluno
		// 			//prepara o aluno e joga na sessao
		// 			$aluno = isAluno();
			
		// 			$_SESSION["s_aluno"] = serialize($aluno);
			
		// 			//redirecionar pra index
		// 			$page = "index.php";
			
			

		// 		}

		// 		if(tipo == "usuario"){
		// 			//se for um usuario adm
		// 			$usuario = isUsuario();
			
		// 			$_SESSION["s_usuario_logado"] = serialize($usuario);
			
		// 			//redirecionar pra index admin
		// 			$page = "usuarios.php";
			
			
		// 		}



		//pega o processo de avalia��o ativo
		//pega dados do processo de avaliacao
		$processo = new ProcessoAvaliacao();
		$processo->get(1);
			
		$_SESSION["s_processo"] = serialize($processo);
		$_SESSION["s_periodo"] = "2/2011";
			
		redirectTo($page);
			
			
		// 			isProfessor();
			
		// 			isCoordenador();
			
		// 			isFuncionario();

			
	}

	//cria um array pra armazenar as questoes com as respectivas notas
	$questoesNotas = array();
	$cont = 0;

	if($action == "logout"){
		logout();
			
	}

}

/**
 * @name logout
 * @author Fabio Ba�a
 * @since 23/02/2012 18:30:26
 * desconecta da sessao e destroi os dados da mesma
 **/
function logout() {
	//Destruir sessao
	// primeiro destru�mos os dados associados � sess�o
	$_SESSION = array();

	// destru�mos ent�o o cookie relacionado a esta sess�o
	if(isset($_COOKIE[session_name()])){
		setcookie(session_name(), '', time() - 1000, '/');
	}

	// finalmente destruimos a sess�o
	session_destroy();

	//fim da destruicao

	//header("Location: login.php?msg=Voc� fez o logout agora.");
	header("Location: http://faculdadeunicampo.edu.br/ca/sistema_avaliacao/View/login.php?msg=Voc� fez o logout agora");
}

/**
 * @name isAluno
 * @author Fabio Ba�a
 * @since 22/02/2012 20:56:00
 * verifica se o usuario � um aluno
 **/
function isAluno($login, $senha) {

	$usuarioLogado = new Aluno();
	$usuarioLogado->login = $login;
	$usuarioLogado->senha = $senha;


	$qtd = $usuarioLogado->find(true);
	//echo "alunos: ".$qtd;
	// 		echo "qtd: ".$qtd;
	// 		echo "<br />";

	if($qtd == 0){
		// 			echo "aluno zero";
		//n�o encontrou retorna false
		return false;
	}
	else{
		// 			echo "� um aluno: ".$aluno->getNome();
		//encontrou retorna o aluno
		return $usuarioLogado;
	}

}

/**
 * @name isUsuario
 * @author Fabio Ba�a
 * @since 23/02/2012 17:19:18
 * verifica se o usuario � um administrador
 **/
function isUsuario($login, $senha) {

	$usuarioLogado = new Usuario();
	$usuarioLogado->login = $login;
	$usuarioLogado->senha = $senha;

	$qtd = $usuarioLogado->find(true);
	//echo "usuarios: ".$qtd;
	if($qtd == 0){
		//n�o encontrou retorna false
		return false;
	}
	else{
		//encontrou retorna o usuario
		return $usuarioLogado;
	}
}

/**
 * @name isProfessor
 * @author Fabio Ba�a
 * @since 02/03/2012 14:44:27
 * verifica se o usuario � professor
 **/
function isProfessor($login, $senha) {
	$usuarioLogado = new Professor();
	$usuarioLogado->login = $login;
	$usuarioLogado->senha = $senha;

	$qtd = $usuarioLogado->find(true);
	if($qtd == 0){
		return false;
	}
	else{
		$idProfessor = $usuarioLogado->getId();
		
		$turma = new Turma();
		$turma->alias("turma");
		$turma->select("turma.curso, turma.coordenadorId");
		$turma->where("turma.coordenadorId = ".$idProfessor);
		$turma->group("turma.curso");
		$qtdCursos = $turma->find();
		
		if($qtdCursos != 0){
			$usuarioLogado->setIscoordenador(true);
			$usuarioLogado->save;
		}
		//echo "qtdCursos: ".$qtdCursos;
		
		$cursos_coordenados = array();
		while( $turma->fetch()) {
			//echo $turma->curso." - ".$turma->coordenadorId;
			//echo "<br />";
			$cursos_coordenados[] = $turma->curso;			
		}
		$_SESSION["s_cursos_coordenados"] = $cursos_coordenados;
		//print_r($cursos_coordenados);
		//exit();
		
		return $usuarioLogado;
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