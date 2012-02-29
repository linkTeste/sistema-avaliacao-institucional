<?php
class FuncionarioDTO {

	public $_explicitType = 'entidades.FuncionarioDTO';

	public $id;
	public $nome;
	public $login;
	public $senha;
	public $email;
	public $lotacao;
	public $avaliacoes = array();
}