<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "avaliacao"
 * in 2012-02-16
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Avaliacao extends Lumine_Base {

    
    public $id;
    public $questionarioHasQuestaoQuestionarioId;
    public $questionarioHasQuestaoQuestaoId;
    public $nota;
    public $dataAvaliacao;
    public $processoAvaliacaoId;
    public $professorId;
    public $funcionarioId;
    public $alunoRa;
    public $turmaIdTurma;
    
    
    /**
     * get id
     *
     */
    public function getId() {
    	return $this->id;
    }
    
    /**
     * set id
     * @param Type $value
     *
     */
    public function setId($value) {
    	$this->id = $value;
    }
    /**
     * get questionarioHasQuestaoQuestionarioId
     *
     */
    public function getQuestionarioHasQuestaoQuestionarioId() {
    	return $this->questionarioHasQuestaoQuestionarioId;
    }
    
    /**
     * set questionarioHasQuestaoQuestionarioId
     * @param Type $value
     *
     */
    public function setQuestionarioHasQuestaoQuestionarioId($value) {
    	$this->questionarioHasQuestaoQuestionarioId = $value;
    }
    /**
     * get questionarioHasQuestaoQuestaoId
     *
     */
    public function getQuestionarioHasQuestaoQuestaoId() {
    	return $this->questionarioHasQuestaoQuestaoId;
    }
    
    /**
     * set questionarioHasQuestaoQuestaoId
     * @param Type $value
     *
     */
    public function setQuestionarioHasQuestaoQuestaoId($value) {
    	$this->questionarioHasQuestaoQuestaoId = $value;
    }
    /**
     * get nota
     *
     */
    public function getNota() {
    	return $this->nota;
    }
    
    /**
     * set nota
     * @param Type $value
     *
     */
    public function setNota($value) {
    	$this->nota = $value;
    }
    /**
     * get dataAvaliacao
     *
     */
    public function getDataAvaliacao() {
    	return $this->dataAvaliacao;
    }
    
    /**
     * set dataAvaliacao
     * @param Type $value
     *
     */
    public function setDataAvaliacao($value) {
    	$this->dataAvaliacao = $value;
    }
    /**
     * get processoAvaliacaoId
     *
     */
    public function getProcessoAvaliacaoId() {
    	return $this->processoAvaliacaoId;
    }
    
    /**
     * set processoAvaliacaoId
     * @param Type $value
     *
     */
    public function setProcessoAvaliacaoId($value) {
    	$this->processoAvaliacaoId = $value;
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
     * get funcionarioId
     *
     */
    public function getFuncionarioId() {
    	return $this->funcionarioId;
    }
    
    /**
     * set funcionarioId
     * @param Type $value
     *
     */
    public function setFuncionarioId($value) {
    	$this->funcionarioId = $value;
    }
    /**
     * get alunoRa
     *
     */
    public function getAlunoRa() {
    	return $this->alunoRa;
    }
    
    /**
     * set alunoRa
     * @param Type $value
     *
     */
    public function setAlunoRa($value) {
    	$this->alunoRa = $value;
    }
    /**
     * get turmaIdTurma
     *
     */
    public function getTurmaIdTurma() {
    	return $this->turmaIdTurma;
    }
    
    /**
     * set turmaIdTurma
     * @param Type $value
     *
     */
    public function setTurmaIdTurma($value) {
    	$this->turmaIdTurma = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('avaliacao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('questionarioHasQuestaoQuestionarioId', 'questionario_has_questao_questionario_id', 'int', 11, array('notnull' => true));
        $this->metadata()->addField('questionarioHasQuestaoQuestaoId', 'questionario_has_questao_questao_id', 'int', 11, array('notnull' => true));
        $this->metadata()->addField('nota', 'nota', 'int', 11, array());
        $this->metadata()->addField('dataAvaliacao', 'data_avaliacao', 'datetime', null, array());
        $this->metadata()->addField('processoAvaliacaoId', 'processo_avaliacao_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'ProcessoAvaliacao'));
        $this->metadata()->addField('professorId', 'professor_id', 'int', 11, array('foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Professor'));
        $this->metadata()->addField('funcionarioId', 'funcionario_id', 'int', 11, array('foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Funcionario'));
        $this->metadata()->addField('alunoRa', 'aluno_ra', 'varchar', 45, array('foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'ra', 'class' => 'Aluno'));
        $this->metadata()->addField('turmaIdTurma', 'turma_id_turma', 'int', 11, array('foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'idTurma', 'class' => 'Turma'));

        
    }

    #### END AUTOCODE


}
