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

require_once '../system/application/models/dao/Usuario.php';
require_once '../system/application/models/dao/Permissao.php';
require_once '../system/application/models/dao/UsuarioHasPermissao.php';

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

		
	usuarioController();

	/**
	 * @name usuarioController
	 * @author Fabio Ba�a
	 * @since 23/02/2012 15:14:49
	 * fun��o que verifica a action e direciona para a action espec�fica
	 **/
	function usuarioController() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$action = $_POST["action"];
		}
		
		if(isset($_GET["action"])){
			$action = $_GET["action"];
		}

		if($action == "new"){
						
			$usuario = new Usuario();
			
			$_SESSION["action"] = $action;
			$_SESSION["s_usuario"] = serialize($usuario);
			$_SESSION["s_permissoes"] = array();
			
			$page = "usuarios.php";
			redirectTo($page);
		}
		if($action == "edit"){
			//se for edicao pega o id do questionario que ser� editado
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
						
			$usuario = new Usuario();
			$usuario->get($id);
			
			//obter as permissoes atuais do usuario
			$permissoes = array();
			$permissoes_atuais = new UsuarioHasPermissao();
			$permissoes_atuais->usuarioId = $id;
			
			$permissoes_atuais->find();
			while ($permissoes_atuais->fetch()) {
				$permissoes[] = $permissoes_atuais->getPermissaoId();
			}
			
						
			$_SESSION["action"] = $action;
			$_SESSION["s_usuario"] = serialize($usuario);
			$_SESSION["s_permissoes"] = $permissoes;
			
			$page = "usuarios.php";
			redirectTo($page);
			
		}
		if($action == "delete"){
			//se for edicao pega o id do questionario que ser� excluido
			if(isset($_GET["id"])){
				$id = $_GET["id"];
			}
		
			$usuario = new Usuario();
			$usuario->get($id);
			
			//primeiro deletamos as permissoes do usuario
			$permissoes_atuais = new UsuarioHasPermissao();
			$permissoes_atuais->usuarioId = $id;
				
			$permissoes_atuais->find();
			while ($permissoes_atuais->fetch()) {
				$permissoes_atuais->delete();
			}
			
			//deletamos as permissoes da sessao
			$_SESSION["s_permissoes"] = null;
				
			$usuario->delete();
			
			//colocar mensagem de sucesso na sessao
			
					
			$page = "usuarios.php";
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
	 * @author Fabio Ba�a
	 * @since 12/01/2012
	 * fun��o responsavel por cadastrar/atualizar um questionario
	 **/
	function save() {
		$id;
		$nome;
		$login;
		$email;
				
		if(isset($_POST["id"])){
			if($_POST["id"] == ""){
				$id = 0;
			}else{
				$id = $_POST["id"];
			}
		}
			
		if(isset($_POST["nome"])){
			$nome = utf8_decode($_POST["nome"]);
		}
		
		if(isset($_POST["login"])){
			$login = utf8_decode($_POST["login"]);
		}
			
		if(isset($_POST["email"])){
			$email = $_POST["email"];
		}
		
		$permissoes = array();
		if(isset($_POST["permissoes"])){
			foreach($_POST["permissoes"] as $key => $value) {
				$permissoes[] = $value;	
			}
			//print_r($permissoes);			
		}
		
		
			
		$usuario = new Usuario();
		$usuario->setId($id);
		$usuario->setNome($nome);
		$usuario->setLogin($login);
		$usuario->setSenha($login);
		$usuario->setEmail($email);
		
		
		//obter as permissoes atuais do usuario e remove-las pra depois inserir novas
		$permissoes_atuais = new UsuarioHasPermissao();
		$permissoes_atuais->usuarioId = $id;
		
		$permissoes_atuais->find();
		while ($permissoes_atuais->fetch()) {
			$permissoes_atuais->delete();
		}
		
				
		//define as novas permissoes do usuario
		$usuario->setPermissoes($permissoes);
	
		
		
		$usuario->setDataCriacao(date('Y-m-d H:i:s'));
		$usuario->save();

			
		$_SESSION["action"] = $action;
		$_SESSION["s_processo"] = $processo;
		$_SESSION["s_permissoes"] = $permissoes;
		$_SESSION["s_mensagem"] = $mensagem;
		
		
		$page = "usuarios.php";
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
