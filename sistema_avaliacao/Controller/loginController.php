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
require_once '../system/application/models/dao/UsuarioHasPermissao.php';
require_once '../system/application/models/dao/Professor.php';
require_once '../system/application/models/dao/Funcionario.php';
require_once '../system/application/models/dao/Avaliacao.php';
require_once '../system/application/models/dao/ProcessoAvaliacao.php';
require_once '../system/application/models/dao/Comentarios.php';
require_once '../system/application/models/dao/Log.php';

require '../Utils/functions.php';

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

//pegar isso dinamicamente
$periodo_atual = "1/2012";


loginController();

/**
 * @name loginController
 * @author Fabio Ba�a
 * @since 22/02/2012 20:48:53
 * fun��o que verifica a action e direciona para a action espec�fica
 **/
function loginController() {
	$_POST = validaParametro($_POST);
	$_GET = validaParametro($_GET);

	//fazer o tratamento aqui da codificacao utf-8, iso, etc
	if(isset($_POST["action"])){
		$action = $_POST["action"];
	}
	if(isset($_GET["action"])){
		$action = $_GET["action"];
	}



	if($action == "recuperar"){
		if(isset($_POST["usuario"])){
			$login = $_POST["usuario"];
		}

		recoveryPassword($login);

	}

	if($action == "logar"){
		//pega o usuario e a senha
		if(isset($_POST["usuario"])){
			$login = $_POST["usuario"];
		}
		
		if(isset($_POST["senha"])){
			$senha = $_POST["senha"];
		}
		
		login($login, $senha);
	}

	//adaptado para funcionar com o acesso externo
	if(isset($_SESSION["acesso_externo"]) && $_SESSION["acesso_externo"] == "true"){

// 		echo $_SESSION[user];
// 		echo "<br />";
// 		echo $_SESSION[senha];
// 		echo "<br />";
// 		echo $_SESSION[nivel_acesso];
		
		if(isset($_SESSION[nivel_acesso]) && $_SESSION[nivel_acesso] == "aluno"){
			$login = $_SESSION[user];
			$senha = $_SESSION[senha];

		}	
		
		if(isset($_SESSION[nivel_acesso]) && $_SESSION[nivel_acesso] == "professor"){
			$login = $_SESSION[usuario];
			$senha = $_SESSION[senha];
					
		}
		
		login($login, $senha);

	}


	if($action == "logar_old" ){

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
				
			$type = "Aluno";
			//registra log
			registraLog($usuarioLogado->getRa(), $type);
				
			$_SESSION["s_usuario_logado_type"] = $type;
			$_SESSION["s_aluno"] = serialize($usuarioLogado);
				
				
			$page = "index.php";

		}else {
			$usuarioLogado = isUsuario($login, $senha);
				
			if($usuarioLogado != false){
				$type = "Admin";
				//registra log
				registraLog($usuarioLogado->getId(), $type);

				$_SESSION["s_usuario_logado_type"] = $type;
				$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);

				//obtem as permissoes do usuario logado e joga na sessao
				$permissoes = array();
				$permissoes_atuais = new UsuarioHasPermissao();
				$permissoes_atuais->usuarioId = $usuarioLogado->getId();
					
				$permissoes_atuais->find();
				while ($permissoes_atuais->fetch()) {
					$permissoes[] = $permissoes_atuais->getPermissaoId();
				}
					
				$_SESSION["s_usuario_logado_permissoes"] = $permissoes;

				$page = "usuarios.php";
					
			}else{
				$usuarioLogado = isProfessor($login, $senha);
				if($usuarioLogado != false){
						
						
					//verifica se ele � coordenador
					if($usuarioLogado->getIscoordenador() == true){
						$type = "Coordenador";
						$page = "indexCoordenador.php";

					}else{
						$type = "Professor";
						$page = "indexProfessor.php";
					}
						
					//registra log
					registraLog($usuarioLogado->getId(), $type);
						
					$_SESSION["s_usuario_logado_type"] = $type;
					$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);
						
						

				}else{
					$usuarioLogado = isFuncionario($login, $senha);

					if($usuarioLogado != false){
						$type = "Funcionario";
						//registra log
						registraLog($usuarioLogado->getId(), $type);
							
						$_SESSION["s_usuario_logado_type"] = $type;
						$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);

						$page = "indexFuncionario.php";
							
					}
				}
			}
		}




		//pega o processo de avalia��o ativo
		//pega dados do processo de avaliacao
		$processo = new ProcessoAvaliacao();

		$processo->where("ativo='Ativo'");
		$processo->find();
		$processo->fetch(true);
		// $processo->get(1);

		$_SESSION["s_processo"] = serialize($processo);
		// 		$_SESSION["s_periodo"] = "2/2011";
		$_SESSION["s_periodo"] = "1/2012";
			
		redirectTo($page);
			
			
		// 			isProfessor();
			
		// 			isCoordenador();
			
		// 			isFuncionario();

			
	}

	//cria um array pra armazenar as questoes com as respectivas notas
	//$questoesNotas = array();
	//$cont = 0;

	if($action == "logout"){
		logout();
			
	}

}

/**
 * @name login
 * @author Fabio Baía
 * @since 05/08/2012 16:45:45
 * insert a description here
 **/
function login($login, $senha) {
// 	//primeiro faz logout por precaução
// 	logout();
	
// 	echo "dentro da funcao login";
	//verifica qual o tipo do usuario
	$usuarioLogado = isAluno($login, $senha);
	if($usuarioLogado != false){
			
		$type = "Aluno";
		//registra log
		registraLog($usuarioLogado->getRa(), $type);
			
		$_SESSION["s_usuario_logado_type"] = $type;
		$_SESSION["s_aluno"] = serialize($usuarioLogado);
			
			
		$page = "index.php";

	}else {
		$usuarioLogado = isUsuario($login, $senha);
			
		if($usuarioLogado != false){
			$type = "Admin";
			//registra log
			registraLog($usuarioLogado->getId(), $type);

			$_SESSION["s_usuario_logado_type"] = $type;
			$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);

			//obtem as permissoes do usuario logado e joga na sessao
			$permissoes = array();
			$permissoes_atuais = new UsuarioHasPermissao();
			$permissoes_atuais->usuarioId = $usuarioLogado->getId();

			$permissoes_atuais->find();
			while ($permissoes_atuais->fetch()) {
				$permissoes[] = $permissoes_atuais->getPermissaoId();
			}

			$_SESSION["s_usuario_logado_permissoes"] = $permissoes;

			$page = "usuarios.php";

		}else{
			$usuarioLogado = isProfessor($login, $senha);
			if($usuarioLogado != false){
					
					
				//verifica se ele � coordenador
				if($usuarioLogado->getIscoordenador() == true){
					$type = "Coordenador";
					$page = "indexCoordenador.php";

				}else{
					$type = "Professor";
					$page = "indexProfessor.php";
				}
					
				//registra log
				registraLog($usuarioLogado->getId(), $type);
					
				$_SESSION["s_usuario_logado_type"] = $type;
				$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);
					
					

			}else{
				$usuarioLogado = isFuncionario($login, $senha);

				if($usuarioLogado != false){
					$type = "Funcionario";
					//registra log
					registraLog($usuarioLogado->getId(), $type);

					$_SESSION["s_usuario_logado_type"] = $type;
					$_SESSION["s_usuario_logado"] = serialize($usuarioLogado);

					$page = "indexFuncionario.php";

				}//if
			}//else
		}//else
		
		$msg = "Usuário inválido. Tente novamente.";
	}//else

	//pega o processo de avalia��o ativo
	//pega dados do processo de avaliacao
	$processo = new ProcessoAvaliacao();

	$processo->where("ativo='Ativo'");
	$processo->find();
	$processo->fetch(true);

	$_SESSION["mensagem"] = $msg;
	$_SESSION["s_processo"] = serialize($processo);
	$_SESSION["s_periodo"] = "1/2012";

	redirectTo($page);
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
	
	//destruir cada variavel individualmente - verificar o motivo depois
// 	$_SESSION["s_usuario_logado_type"] = null;
// 	$_SESSION["s_usuario_logado"] = null;
// 	$_SESSION["s_alino"] = null;
// 	$_SESSION["s_processo"] = null;
// 	$_SESSION["s_periodo"] = null;
	//

	// finalmente destruimos a sess�o
	session_destroy();

	//fim da destruicao

	//pega a mensagem de status
	$_SESSION["mensagem"] = "Você fez logout agora";

	//header("Location: login.php?msg=Voc� fez o logout agora.");
	header("Location: http://ca.faculdadeunicampo.edu.br/sistema_avaliacao/View/login.php");

}

/**
 * @name isAluno
 * @author Fabio Baía
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
	
	//se for acesso externo não usa o md5
	if($_SESSION["acesso_externo"] == "true"){
		$usuarioLogado->senha = $senha;
	}else{
		$usuarioLogado->senha = md5($senha);
	}
	
	global $periodo_atual;

	$qtd = $usuarioLogado->find(true);
	if($qtd == 0){
		return false;
	}
	else{
		$idProfessor = $usuarioLogado->getId();

		$turma = new Turma();
		$turma->alias("turma");
		$turma->select("turma.curso, turma.coordenadorId");
		$turma->where("turma.coordenadorId = ".$idProfessor." and turma.periodoLetivo = '".$periodo_atual."'");
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
 * @name isFuncionario
 * @author Fabio Baía
 * @since 14/06/2012 14:19:14
 * verifica se o usuario e um professor
 **/
function isFuncionario($login, $senha) {

	$usuarioLogado = new Funcionario();
	$usuarioLogado->login = $login;
	if($_SESSION["acesso_externo"] == "true"){
		$usuarioLogado->senha = $senha;
	}else{
		$usuarioLogado->senha = md5($senha);
	}
	
	$qtd = $usuarioLogado->find(true);
	//echo "usuarios: ".$qtd;
	if($qtd == 0){
		return false;
	}
	else{
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
 * @name registraLog
 * @author Fabio Baía
 * @since 22/05/2012 16:21:19
 * registra no banco de dados o nome do usuario que logou, hora de acesso e ip
 **/
function registraLog($usuarioId, $tipoUsuario) {
	$ip = $_SERVER['REMOTE_ADDR'];
	$agora = date('Y-m-d H:i:s');

	$log = new Log();
	$log->setId(0);
	$log->setUsuario($usuarioId);
	$log->setTipoUsuario($tipoUsuario);
	$log->setHora($agora);
	$log->setIp($ip);

	$log->save();
}


/**
 * @name recoveryPassword
 * @author Fabio Baía
 * @since 03/08/2012 13:14:08
 * funcao para recuperação de senha
 **/
function recoveryPassword($param) {

	$usuario = new Aluno;
	$usuario->login = $param;

	$qtd = $usuario->find(true);
	//encontra o usuario
	//pega o nome dele, extrai o primeiro nome
	//concatena o ra+primeironome+@faculdadeunicampo.edu.br


	//msg os dados de acesso foram enviados para <<email do individuo>>

	if($qtd == 0){
		// 		return false;
		$msg_status = "Usuário inválido. Tente novamente.";
		$page = "recuperar_senha.php";
	}
	else{
		$nome = explode(" ", $usuario->getNome());
		$primeiroNome = $nome[0];

		//remove os acentos pra montar o endereco de email corretamente
		$primeiroNome = strtolower($primeiroNome);
		$primeiroNome = ereg_replace("[áàâãª]","a",$primeiroNome);
		$primeiroNome = ereg_replace("[éèê]","e",$primeiroNome);
		$primeiroNome = ereg_replace("[óòôõº]","o",$primeiroNome);
		$primeiroNome = ereg_replace("[úùû]","u",$primeiroNome);
		$primeiroNome = str_replace("ç","c",$primeiroNome);

		$to = $primeiroNome.$usuario->getRa()."@faculdadeunicampo.edu.br";

		//envia email
		$assunto = "Dados para acessar o Sistema de Avaliação";
		$msg = "Seus dados de acesso são: <br />";
		$msg .= "Usuario: ".$usuario->getLogin()."<br />";
		$msg .= "Senha: ".$usuario->getSenha()."<br />";

		$msg .= "<br />Atenciosamente,<br />";
		$msg .= "Faculdade Unicampo.<br />";


		$status_envio = sendEmail($to, $assunto, $msg);

		$msg_status;
		if ($status_envio!=true){
			$msg_status = "Ocorreu um erro ao enviar a mensagem. Tente novamente mais tarde.";
			//die();
		}else{
			$msg_status = "Os dados de acesso ao Sistema de avaliação foram enviados para o e-mail: ";
			$msg_status .= "<br />";
			$msg_status .= "<span>".$to."</span>";
		}

		$page = "login.php";
	}

	//redireciona para pagina de login
	$_SESSION["mensagem"] = $msg_status;

	redirectTo($page);

}

/**
 * @name sendEmail
 * @author Fabio Baía
 * @since 03/08/2012 14:10:40
 * envia email
 **/
function sendEmail($to, $assunto, $msg) {

	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
	$headers .= "From: UNICAMPO <suporte.ti@faculdadeunicampo.edu.br> \r\n";

	//debug
	//$to = "fabio.ti@faculdadeunicampo.edu.br";

	$send_check=mail($to,$assunto,$msg,$headers);

	return $send_check;

}

/***************** FUNÇÕES PARA TRATAR INJEÇÃO DE CÓDIGO ****************************/
function mb_sql_regcase($string,$encoding='auto'){
	$max=mb_strlen($string,$encoding);
	$ret='';
	for ($i = 0; $i < $max; $i++) {
		$char=mb_substr($string,$i,1,$encoding);
		$up=mb_strtoupper($char,$encoding);
		$low=mb_strtolower($char,$encoding);
		$ret.=($up!=$low)?'['.$up.$low.']' : $char;
	}
	return $ret;
}

/**
* @name antiInjection
* @author Fabio Baía
* @since 06/08/2012 15:12:16
* Tratamento de injections em formulários
**/
function antiInjection($str)
{
	# Remove palavras suspeitas de injection.
	$str = preg_replace(mb_sql_regcase("/(\n|\r|%0a|%0d|Content-Type:|bcc:|to:|cc:|Autoreply:|from|select|insert|delete|where|drop table|drop database|kill|system|update|like|alter table|drop|\;|show tables|#|\*|--|\\\\)/"), "", $str);
$str = trim($str); # Remove espaços vazios.
$str = strip_tags($str); # Remove tags HTML e PHP.
$str = addslashes($str); # Adiciona barras invertidas à uma string.
return $str;
}

/**
* @name validaParametro
* @author Fabio Baía
* @since 06/08/2012 15:13:34
* Antes de tratar os injections, verifica se é vetor ou não
**/
function validaParametro($vetor)
{
	if (is_array($vetor))
	{
		foreach ($vetor as $chave => $valor)
		{
			if (is_array($valor))
			{
				$vetor[$chave] = validaParametro($valor);
			} else $vetor[$chave] = antiInjection($valor);
		}
	} else $vetor[$chave] = validaParametro($valor);
	return $vetor;
}

//}

?>