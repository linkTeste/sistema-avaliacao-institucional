<?php

/**
 * @name questionario
 * @author Fabio Baía
 * @since 12/01/2012
 * Bean do questionario
 */
class questionario {
	private $id;
	private $descricao;
	private $instrumento_id;
	private $questoes = array();

	public function setId($id){
		$this->id = $id;
	}

	public function getId(){
		return $this->id;
	}

	public function setDescricao($descricao){
		$this->descricao = $descricao;
	}

	public function getDescricao(){
		return $this->descricao;
	}

	public function setInstrumento_id($instrumento_id){
		$this->instrumento_id = $instrumento_id;
	}

	public function getInstrumento_id(){
		return $this->instrumento_id;
	}
	
	/**
	* @name addQuestao
	* @author Fabio Baía
	* @since 23/01/2012 17:29:45
	* adiciona uma questao ao array de questoes
	**/
	public function addQuestao(questao $questao){
		array_push($this->questoes, $questao);
	}
	
	/**
	* @name getQuestoes
	* @author Fabio Baía
	* @since 23/01/2012 17:30:25
	* retorna um array com as questoes desse questionario
	**/
	public function getQuestoes(){
		return $this->questoes;
	}
}

?>