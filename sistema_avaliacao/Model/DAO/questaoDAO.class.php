<?php
/**
* @name questaoDAO
* @author Fabio Baнa
* @since 18/01/2012
* insert a description here
*/
class questaoDAO {
	private $table = "questao";
	private $table_alias ="q";
	private $tableJoin = "questionario_has_questao";
	private $tableJoin_alias = "qhq";
	private $pdo;
	private $UNSAVED_ID = 0;
	
	//database
	private $host = "mysql01-farm26.kinghost.net";
	private $db_name = "faculdadeunica05";
	private $user = "faculdadeunica05";
	private $password = "avaliacaounicampo159";

	//construtor
	function __construct(){
		$this->connect();
	}

	/**
	* @name add
	* @author Fabio Baнa
	* @since 18/01/2012 15:54:15
	* adiciona uma nova questao
	**/
	public function add(questao $questao, $questionario_id) {
		$texto = $questao->getTexto();
		$topico = $questao->getTopico();
		
		//pega o id do questionario
		//$questionario_id = $questao->
		
		$data_criacao = date('Y-m-d H:i:s');
		
		$stmt = $this->pdo->prepare("INSERT INTO ".$this->table." (texto, topico, data_criacao) VALUES (:texto, :topico, :data_criacao)");

		// Fazendo o binding
		$stmt->bindParam(":texto", $texto, PDO::PARAM_STR);
		$stmt->bindParam(":topico", $topico, PDO::PARAM_STR);
		$stmt->bindParam(":data_criacao", $data_criacao, PDO::PARAM_STR);

		// Executando a SQL com os valores definidos com binding
		$consultou = $stmt->execute();
		
		//pega o id da ultima questao cadastrada
		$questao_id = $this->pdo->lastInsertId();		
		//faz a insercao na tabela join
		$stmt2 = $this->pdo->prepare("INSERT INTO ".$this->tableJoin." (questionario_id, questao_id) VALUES (:questionario_id, :questao_id)");
		$stmt2->bindParam(":questionario_id", $questionario_id, PDO::PARAM_INT);
		$stmt2->bindParam(":questao_id", $questao_id, PDO::PARAM_INT);
		$consultou2 = $stmt2->execute();
		
		return true;

	}

	/**
	* @name persiste
	* @author Fabio Baнa
	* @since 18/01/2012 15:58:43
	* persiste uma questao no banco de dados, criando uma nova questao ou atualizando uma existente
	**/
	public function persiste(questao $questao) {
		$id = $questao->getId();

		if($questao->getId() == $this->UNSAVED_ID){
			$this->add($questao);
		}else{
			$this->update($questao);
		}
	}

	
	/**
	* @name remove
	* @author Fabio Baнa
	* @since 18/01/2012 16:01:10
	* remove uma questao do banco
	**/
	public function remove($id) {
		$where = " WHERE id = ".$id;
		$sql = "DELETE FROM ".$this->table.$where;
		$result = $this->pdo;
		$result->beginTransaction();
		if ($result->exec($sql)){
			$result->commit();
		}else{
			$result->rollBack();
		}		
		
	}

	
	/**
	* @name get
	* @author Fabio Baнa
	* @since 18/01/2012 16:02:17
	* obtem uma questao do banco com base em um id
	**/
	public function get($id) {
		$where = " WHERE id = ".$id;
		$questao = new questao();

		//faz o select e busca o registro no banco
		$sql = "SELECT id, texto, topico FROM ".$this->table.$where;

		//percorre o resultset e pega o registro
		$result = $this->pdo->query($sql)->fetch();
		
		$texto;
		$topico;
				
		$id = $result["id"];
		$texto = $result["texto"];
		$topico = $result["topico"];
			
		$questao->setId($id);
		$questao->setTexto($texto);
		$questao->setTopico($topico);
		
		return $questao;
	}


	/**
	* @name listAll
	* @author Fabio Baнa
	* @since 18/01/2012 16:06:16
	* Lista todos as questoes do banco
	**/
	public function listAll($ordem = null) {
		if($ordem == "desc" || $order == null){
			$order = " ORDER BY id DESC";
		}
		if($ordem == "asc"){
			$order = " ORDER BY id ASC";
		}
		
		//$list_questionario = array();
		$sql = "SELECT * FROM ".$this->table.$order;
		$list_questionario = $this->pdo->query($sql)->fetchAll();
		
		return $list_questionario;
	}
	
	/**
	* @name listById
	* @author Fabio Baнa
	* @since 20/01/2012 15:27:31
	* lista todos as questoes em um determinado questionario
	**/
	public function listById(questionario $questionario) {
		$id = $questionario->getId();
		$where = "WHERE qhq.questionario_id = ".$id." and qhq.questao_id = q.id";
		$sql = "SELECT  q.* from questao q, questionario_has_questao qhq ".$where;
		$list_questionario = $this->pdo->query($sql)->fetchAll();
		
		return $list_questionario;
	}
	

	
	/**
	 * @name connect
	 * @author Fabio Baнa
	 * @since 12/01/2012
	 * funзгopara conexao com o banco de dados
	 **/
	public function connect() {
		$this->pdo = new PDO("mysql:host=$this->host;dbname=$this->db_name", $this->user, $this->password);
	}


	
	/**
	* @name update
	* @author Fabio Baнa
	* @since 18/01/2012 16:07:14
	* atualiza uma questao no banco de dados
	**/
	public function update(questao $questao) {
		$id = $questao->getId();
		$texto = $questao->getTexto();
		$topico = $questao->getTopico();
		
		$stmt = $this->pdo->prepare("UPDATE ".$this->table." SET texto = :texto,
		topico = :topico WHERE id = :id");
		
		// Fazendo o binding
		$stmt->bindParam(":id", $id, PDO::PARAM_INT);
		$stmt->bindParam(":texto", $texto, PDO::PARAM_STR);
		$stmt->bindParam(":topico", $topico, PDO::PARAM_STR);
		
		// Executando a SQL com os valores definidos com binding
		$consultou = $stmt->execute();
		
		return true;
		
	}



}




?>