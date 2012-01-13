<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual página chamar de acordo com a action

/**
 * @name questionarioController
 * @author Fabio Baía
 * @since 12/01/2012
 * controller do questionario
 */
class questionarioController {
	private $action;
	private $page;
	private $default_page = "home.php";

	private $questionario;
	private $questionarioDAO;


	/**
	 * @name control
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que verifica a action e direciona para a action específica
	 **/
	public function control() {
		//fazer o tratamento aqui da codificacao utf-8, iso, etc
		if(isset($_POST["action"])){
			$this->action = $_POST["action"];
		}

		if($this->action == "new"){
			//redireciona para pagina de cadastro
			//como vou tentar fazer tudo em uma view só a action definira quais partes da view devem ser mostradas
		}
		if($this->action == "edit"){
			//se for edicao pega o id do questionario que será editado
			if(isset($_POST["id"])){
				$id = $_POST["id"];
			}
						
			$this->questionarioDAO = new questionarioDAO();
			$this->questionario = $this->questionarioDAO->get($id);
			
			$this->prepareSession($this->questionario);
			$this->page = "questionario.php";
			$this->redirectTo($this->page);
			
		}
	}

	/**
	 * @name save
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função responsavel por cadastrar/atualizar um questionario
	 **/
	public function save() {
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
			
		if(isset($_POST["instrumento_id"])){
			$instrumento_id = $_POST["instrumento_id"];
		}
			
		$this->questionario = new questionario();
		$this->questionario->setId($id);
		$this->questionario->setDescricao($descricao);
		$this->questionario->setInstrumento_id($instrumento_id);
			
		$this->questionarioDAO = new questionarioDAO();
		$status = $this->questionarioDAO->add($questionario);
			
		if($status = true){
			//cadastrado com sucesso
			$this->page = "listar_questionario.php";
		}
			
		$this->redirectTo($this->page);
	}

	
	/**
	 * @name prepareSession
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que lança dados na sessão
	 **/
	public function prepareSession(string $nome, questionario $questionario) {
		//prepara a sessao
		//seta valores na sessao
		;
	}

	/**
	 * @name redirectTo
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que redireciona pra uma pagina específica
	 **/
	public function redirectTo($url) {
		header($url);
	}


}



