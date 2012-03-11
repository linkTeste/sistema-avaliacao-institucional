<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "questionario_usado"
 * in 2012-03-10
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class QuestionarioUsado extends Lumine_Base {

    
    public $id;
    public $tipo;
    public $subtipo;
    public $questionarioId;
    public $serie;
    public $processoAvaliacaoId;
    
    
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
     * get tipo
     *
     */
    public function getTipo() {
    	return $this->tipo;
    }
    
    /**
     * set tipo
     * @param Type $value
     *
     */
    public function setTipo($value) {
    	$this->tipo = $value;
    }
    /**
     * get subtipo
     *
     */
    public function getSubtipo() {
    	return $this->subtipo;
    }
    
    /**
     * set subtipo
     * @param Type $value
     *
     */
    public function setSubtipo($value) {
    	$this->subtipo = $value;
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
     * get serie
     *
     */
    public function getSerie() {
    	return $this->serie;
    }
    
    /**
     * set serie
     * @param Type $value
     *
     */
    public function setSerie($value) {
    	$this->serie = $value;
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
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('questionario_usado');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('tipo', 'tipo', 'varchar', 45, array());
        $this->metadata()->addField('subtipo', 'subtipo', 'varchar', 45, array());
        $this->metadata()->addField('questionarioId', 'questionario_id', 'int', 11, array());
        $this->metadata()->addField('serie', 'serie', 'varchar', 45, array());
        $this->metadata()->addField('processoAvaliacaoId', 'processo_avaliacao_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'ProcessoAvaliacao'));

        
    }

    #### END AUTOCODE


}
