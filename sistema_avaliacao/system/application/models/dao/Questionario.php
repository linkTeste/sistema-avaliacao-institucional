<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "questionario"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Questionario extends Lumine_Base {

    
    public $id;
    public $descricao;
    public $instrumentoId;
    public $dataCreate;
    public $avaliado;
    public $questionariohasquestoes = array();
    public $turmas = array();
    public $questoes = array();
    
    
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
     * get descricao
     *
     */
    public function getDescricao() {
    	return $this->descricao;
    }
    
    /**
     * set descricao
     * @param Type $value
     *
     */
    public function setDescricao($value) {
    	$this->descricao = $value;
    }
    /**
     * get instrumentoId
     *
     */
    public function getInstrumentoId() {
    	return $this->instrumentoId;
    }
    
    /**
     * set instrumentoId
     * @param Type $value
     *
     */
    public function setInstrumentoId($value) {
    	$this->instrumentoId = $value;
    }
    /**
     * get dataCreate
     *
     */
    public function getDataCreate() {
    	return $this->dataCreate;
    }
    
    /**
     * set dataCreate
     * @param Type $value
     *
     */
    public function setDataCreate($value) {
    	$this->dataCreate = $value;
    }
    /**
     * get avaliado
     *
     */
    public function getAvaliado() {
    	return $this->avaliado;
    }
    
    /**
     * set avaliado
     * @param Type $value
     *
     */
    public function setAvaliado($value) {
    	$this->avaliado = $value;
    }
    /**
     * get questionariohasquestoes
     *
     */
    public function getQuestionariohasquestoes() {
    	return $this->questionariohasquestoes;
    }
    
    /**
     * set questionariohasquestoes
     * @param Type $value
     *
     */
    public function setQuestionariohasquestoes($value) {
    	$this->questionariohasquestoes = $value;
    }
    /**
     * get turmas
     *
     */
    public function getTurmas() {
    	return $this->turmas;
    }
    
    /**
     * set turmas
     * @param Type $value
     *
     */
    public function setTurmas($value) {
    	$this->turmas = $value;
    }
    /**
     * get questoes
     *
     */
    public function getQuestoes() {
    	return $this->questoes;
    }
    
    /**
     * set questoes
     * @param Type $value
     *
     */
    public function setQuestoes($value) {
    	$this->questoes = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('questionario');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('descricao', 'descricao', 'varchar', 255, array());
        $this->metadata()->addField('instrumentoId', 'instrumento_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Instrumento'));
        $this->metadata()->addField('dataCreate', 'data_create', 'datetime', null, array());
        $this->metadata()->addField('avaliado', 'avaliado', 'varchar', 45, array());

        
        $this->metadata()->addRelation('questionariohasquestoes', Lumine_Metadata::ONE_TO_MANY, 'QuestionarioHasQuestao', 'questionarioId', null, null, null);
        $this->metadata()->addRelation('turmas', Lumine_Metadata::ONE_TO_MANY, 'Turma', 'questionarioId', null, null, null);
        $this->metadata()->addRelation('questoes', Lumine_Metadata::MANY_TO_MANY, 'Questao', 'id', 'questionario_has_questao', 'questionario_id', null);
    }

    #### END AUTOCODE


}
