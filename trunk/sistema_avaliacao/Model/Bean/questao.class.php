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
}