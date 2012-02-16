<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "turma"
 * in 2012-02-16
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Turma extends Lumine_Base {

    
    public $idTurma;
    public $nomeDisciplina;
    public $periodoLetivo;
    public $curso;
    public $questionarioId;
    public $professorId;
    public $avaliacoes = array();
    public $comentarios = array();
    public $alunos = array();
    
    
    /**
     * get idTurma
     *
     */
    public function getIdTurma() {
    	return $this->idTurma;
    }
    
    /**
     * set idTurma
     * @param Type $value
     *
     */
    public function setIdTurma($value) {
    	$this->idTurma = $value;
    }
    /**
     * get nomeDisciplina
     *
     */
    public function getNomeDisciplina() {
    	return $this->nomeDisciplina;
    }
    
    /**
     * set nomeDisciplina
     * @param Type $value
     *
     */
    public function setNomeDisciplina($value) {
    	$this->nomeDisciplina = $value;
    }
    /**
     * get periodoLetivo
     *
     */
    public function getPeriodoLetivo() {
    	return $this->periodoLetivo;
    }
    
    /**
     * set periodoLetivo
     * @param Type $value
     *
     */
    public function setPeriodoLetivo($value) {
    	$this->periodoLetivo = $value;
    }
    /**
     * get curso
     *
     */
    public function getCurso() {
    	return $this->curso;
    }
    
    /**
     * set curso
     * @param Type $value
     *
     */
    public function setCurso($value) {
    	$this->curso = $value;
    }
    /**
     * get questionarioId
     *
     */
    public function getQuestionarioId() {
    	return $this->questionarioId;
    }
    
    /**
     * set questionarioId
     * @param Type $value
     *
     */
    public function setQuestionarioId($value) {
    	$this->questionarioId = $value;
    }
    /**
     * get professorId
     *
     */
    public function getProfessorId() {
    	return $this->professorId;
    }
    
    /**
     * set professorId
     * @param Type $value
     *
     */
    public function setProfessorId($value) {
    	$this->professorId = $value;
    }
    /**
     * get avaliacoes
     *
     */
    public function getAvaliacoes() {
    	return $this->avaliacoes;
    }
    
    /**
     * set avaliacoes
     * @param Type $value
     *
     */
    public function setAvaliacoes($value) {
    	$this->avaliacoes = $value;
    }
    /**
     * get comentarios
     *
     */
    public function getComentarios() {
    	return $this->comentarios;
    }
    
    /**
     * set comentarios
     * @param Type $value
     *
     */
    public function setComentarios($value) {
    	$this->comentarios = $value;
    }
    /**
     * get alunos
     *
     */
    public function getAlunos() {
    	return $this->alunos;
    }
    
    /**
     * set alunos
     * @param Type $value
     *
     */
    public function setAlunos($value) {
    	$this->alunos = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('turma');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('idTurma', 'id_turma', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nomeDisciplina', 'nome_disciplina', 'varchar', 255, array('notnull' => true));
        $this->metadata()->addField('periodoLetivo', 'periodo_letivo', 'varchar', 45, array('notnull' => true));
        $this->metadata()->addField('curso', 'curso', 'varchar', 255, array('notnull' => true));
        $this->metadata()->addField('questionarioId', 'questionario_id', 'int', 11, array('foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Questionario'));
        $this->metadata()->addField('professorId', 'professor_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Professor'));

        
        $this->metadata()->addRelation('avaliacoes', Lumine_Metadata::ONE_TO_MANY, 'Avaliacao', 'turmaIdTurma', null, null, null);
        $this->metadata()->addRelation('comentarios', Lumine_Metadata::ONE_TO_MANY, 'Comentarios', 'turmaIdTurma', null, null, null);
        $this->metadata()->addRelation('alunos', Lumine_Metadata::MANY_TO_MANY, 'Aluno', 'idTurma', 'turma_has_aluno', 'turma_id_turma', null);
    }

    #### END AUTOCODE


}
