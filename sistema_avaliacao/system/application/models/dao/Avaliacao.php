<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "avaliacao"
 * in 2012-03-13
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
    public $coordenadorId;
    public $itemAvaliado;
    public $avaliador;
    public $tipoAvaliacao;
    
    
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
     * get coordenadorId
     *
     */
    public function getCoordenadorId() {
    	return $this->coordenadorId;
    }
    
    /**
     * set coordenadorId
     * @param Type $value
     *
     */
    public function setCoordenadorId($value) {
    	$this->coordenadorId = $value;
    }
    /**
     * get itemAvaliado
     *
     */
    public function getItemAvaliado() {
    	return $this->itemAvaliado;
    }
    
    /**
     * set itemAvaliado
     * @param Type $value
     *
     */
    public function setItemAvaliado($value) {
    	$this->itemAvaliado = $value;
    }
    /**
     * get avaliador
     *
     */
    public function getAvaliador() {
    	return $this->avaliador;
    }
    
    /**
     * set avaliador
     * @param Type $value
     *
     */
    public function setAvaliador($value) {
    	$this->avaliador = $value;
    }
    /**
     * get tipoAvaliacao
     *
     */
    public function getTipoAvaliacao() {
    	return $this->tipoAvaliacao;
    }
    
    /**
     * set tipoAvaliacao
     * @param Type $value
     *
     */
    public function setTipoAvaliacao($value) {
    	$this->tipoAvaliacao = $value;
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
        $this->metadata()->addField('coordenadorId', 'coordenador_id', 'int', 11, array());
        $this->metadata()->addField('itemAvaliado', 'item_avaliado', 'varchar', 45, array());
        $this->metadata()->addField('avaliador', 'avaliador', 'varchar', 45, array());
        $this->metadata()->addField('tipoAvaliacao', 'tipo_avaliacao', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
