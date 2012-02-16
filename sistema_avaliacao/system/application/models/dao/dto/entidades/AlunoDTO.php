<?php
class AlunoDTO {

	public $_explicitType = 'entidades.AlunoDTO';

	public $id;
	public $nome;
	public $login;
	public $senha;
	public $email;
	public $ra;
	public $curso;
	public $sitAcademica;
	public $avaliacoes = array();
	public $turmas = array();
}