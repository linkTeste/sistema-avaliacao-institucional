<?php

/**
* @name questao
* @author Fabio Baía
* @since 18/01/2012
* insert a description here
*/
class questao {
	private $id;
	private $texto;
	private $topico;
	private $questionarios = array();
	
	
	public function setId($id){
		$this->id = $id;
	}
	
	public function getId(){
		return $this->id;
	}
	
	public function setTexto($texto){
		$this->texto = $texto;
	}
	
	public function getTexto(){
		return $this->texto;
	}
	
	public function setTopico($topico){
		$this->topico = $topico;
	}
	
	public function getTopico(){
		return $this->topico;
	}
	
	/**
	* @name addQuestionario
	* @author Fabio Baía
	* @since 23/01/2012 17:27:22
	* adiciona um questionario ao array de questionarios
	**/
	public function addQuestionario(questionario $questionario) {
		array_push($this->questionarios, $questionario);
	}
	
	/**
	* @name getQuestionarios
	* @author Fabio Baía
	* @since 23/01/2012 17:28:39
	* retorna um array com os questionarios que têem essa questao
	**/
	public function getQuestionarios() {
			return $this->questionarios;
	}
}