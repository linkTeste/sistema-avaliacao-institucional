<?php
class TurmaDTO {

	public $_explicitType = 'entidades.TurmaDTO';

	public $idTurma;
	public $nomeDisciplina;
	public $periodoLetivo;
	public $curso;
	public $questionarioId;
	public $professorId;
	public $alunos = array();
}