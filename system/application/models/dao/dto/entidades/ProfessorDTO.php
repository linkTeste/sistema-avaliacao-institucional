<?php
class ProfessorDTO {

	public $_explicitType = 'entidades.ProfessorDTO';

	public $id;
	public $nome;
	public $login;
	public $senha;
	public $email;
	public $iscoordenador;
	public $avaliacoes = array();
	public $turmas = array();
}