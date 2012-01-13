<?php
/**
* @name questionarioDAO
* @author Fabio Baнa
* @since 12/01/2012
* DAO do questionario
*/
class questionarioDAO{
	private $table = "questionario";
	private $db;
	private $UNSAVED_ID = 0;
	
	//construtor
	function __construct(){
		$this->connect();
	}
	
	//add
	/**
	* @name add
	* @author Fabio Baнa
	* @since 11/01/2012
	* adiciona um novo questionario no bd
	**/
	public function add(questionario $questionario) {
		$descricao = $questionario->getDescricao();
		$instrumento_id = $questionario->getInstrumento_id();
		
		$sql = "INSERT INTO ".$this->table." (descricao, instrumento_id) VALUES ('$descricao', $instrumento_id)";
		
		//terminar isso depois...
		/*** prepare and execute ***/
		$stmt = $this->db->prepare($sql);
		foreach($values as $vals)
		{
			$stmt->execute($vals);
		}
		
		return true;
		
	}
	
	//persiste	
	/**
	* @name persiste
	* @author Fabio Baнa
	* @since 12/01/2012
	* funзгo que persiste um objeto no banco, criando um novo objeto ou atualizando quando necessario
	**/
	public function persiste(questionario $questionario) {
		$id = $questionario->getId();
		
		if($questionario->getId() == $this->UNSAVED_ID){
			$this->add($questionario);
		}else{
			$this->update($questionario);
		}
	}
	//remove
	
	
	//obtem
	/**
	* @name get
	* @author Fabio Baнa
	* @since 12/01/2012
	* obtem um questionario do banco baseado em um id
	**/
	public function get($id) {
		$questionario = new questionario();
		
		//faz o select e busca o registro no banco
		$sql = "SELECT * from ".$this->table." WHERE id = ".$id;
		
		//percorre o resultset e pega o registro
		
		//seta as propriedades do objeto
		
		//return o objeto - unique result
		return $questionario;
	}
	
	
	//list
	
	//connect
	function connect(){
		//conexao aqui
	}
	
	/**
	 * @name insert
	 * @author Fabio Baнa
	 * @since 11/01/2012
	 * @param unknown_type $table
	 * @param unknown_type $values
	 * Mйtodo para inserзгo de questionarios no bd
	 */	
	public function insert($table, $values)
	{
		//pegar o objeto
		$this->conn();
		/*** snarg the field names from the first array member ***/
		$fieldnames = array_keys($values[0]);
		/*** now build the query ***/
		$size = sizeof($fieldnames);
		$i = 1;
		$sql = "INSERT INTO $table";
		/*** set the field names ***/
		$fields = '( ' . implode(' ,', $fieldnames) . ' )';
		/*** set the placeholders ***/
		$bound = '(:' . implode(', :', $fieldnames) . ' )';
		/*** put the query together ***/
		$sql .= $fields.' VALUES '.$bound;
	
		/*** prepare and execute ***/
		$stmt = $this->db->prepare($sql);
		foreach($values as $vals)
		{
			$stmt->execute($vals);
		}
	}
	
	//update
	/**
	* @name update
	* @author Fabio Baнa
	* @since 11/01/2012
	* insert a description here
	**/
	public function update(questionario $questionario) {
	
		//update table-x set ...;
	}
	
	
	
}




?>