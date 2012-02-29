<?php
class QuestionarioDTO {

	public $_explicitType = 'entidades.QuestionarioDTO';

	public $id;
	public $descricao;
	public $instrumentoId;
	public $dataCreate;
	public $turmas = array();
	public $questoes = array();
}