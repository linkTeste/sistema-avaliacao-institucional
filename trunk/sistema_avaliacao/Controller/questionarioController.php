<?php

//pega os paramentros via get, post , sessao

//trabalha com os beans e DAOS

//define qual página chamar de acordo com a action


/**
 * @name questionarioController
 * @author Fabio Baía
 * @since 12/01/2012
 * insert a description here
 */
class questionarioController {
	private $action;
	private $page;
	private $default_page = "home.php";

	private $questionario;
	private $questionarioDAO;


	/**
	 * @name get
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que trata os dados recebidos via get
	 **/
	function get() {
		if(isset($_GET["action"])){
			$this->action = $_GET["action"];
		}

		if($this->action == ""){
			$this->page = $this->default_page;

		}
		if($this->action == "add"){
			$this->page = "add_questionario.php";
		}
		if($this->action == "edit"){
			$this->page = "edit_questionario.php";
		}
	}

	/**
	 * @name post
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que trata os dados recebidos via post
	 **/
	public function post() {
		$id;
		$descricao;
		$instrumento_id;

		if(isset($_POST["action"])){
			$this->action = $_POST["action"];
		}

		if($this->action == "add"){
			$this->add();
		}
		if($this->action == "edit"){
			//$this->page = "edit_questionario.php";
			$this->update();
		}
	}

	/**
	 * @name add
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função responsavel por cadastrar um novo questionario
	 **/
	public function add() {
		if(isset($_POST["id"])){
			$id = $_POST["id"];
		}
			
		if(isset($_POST["description"])){
			$id = $_POST["description"];
		}
			
		if(isset($_POST["instrumento_id"])){
			$id = $_POST["instrumento_id"];
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
	 * @name update
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função responsável por atualizar um questionario existente
	 **/
	public function update($param) {
		;
	}

	/**
	 * @name prepareSession
	 * @author Fabio Baía
	 * @since 12/01/2012
	 * função que lança dados na sessão
	 **/
	public function prepareSession($param) {
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



