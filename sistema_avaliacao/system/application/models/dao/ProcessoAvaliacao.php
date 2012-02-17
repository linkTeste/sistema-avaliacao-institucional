<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "processo_avaliacao"
 * in 2012-02-16
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class ProcessoAvaliacao extends Lumine_Base {

    
    public $id;
    public $inicio;
    public $fim;
    public $dataCriacao;
    public $avaliacoes = array();
    
    
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
     * get inicio
     *
     */
    public function getInicio() {
    	return $this->inicio;
    }
    
    /**
     * set inicio
     * @param Type $value
     *
     */
    public function setInicio($value) {
    	$this->inicio = $value;
    }
    /**
     * get fim
     *
     */
    public function getFim() {
    	return $this->fim;
    }
    
    /**
     * set fim
     * @param Type $value
     *
     */
    public function setFim($value) {
    	$this->fim = $value;
    }
    /**
     * get dataCriacao
     *
     */
    public function getDataCriacao() {
    	return $this->dataCriacao;
    }
    
    /**
     * set dataCriacao
     * @param Type $value
     *
     */
    public function setDataCriacao($value) {
    	$this->dataCriacao = $value;
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
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('processo_avaliacao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('inicio', 'inicio', 'date', null, array());
        $this->metadata()->addField('fim', 'fim', 'date', null, array());
        $this->metadata()->addField('dataCriacao', 'data_criacao', 'datetime', null, array());

        
        $this->metadata()->addRelation('avaliacoes', Lumine_Metadata::ONE_TO_MANY, 'Avaliacao', 'processoAvaliacaoId', null, null, null);
    }

    #### END AUTOCODE


}