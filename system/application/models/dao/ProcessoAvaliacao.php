<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "processo_avaliacao"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class ProcessoAvaliacao extends Lumine_Base {

    
    public $id;
    public $descricao;
    public $inicio;
    public $fim;
    public $dataCriacao;
    public $avaliado;
    public $ativo;
    public $avaliacoes = array();
    public $log = array();
    public $notificacoes = array();
    public $questionariousados = array();
    
    
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
     * get ativo
     *
     */
    public function getAtivo() {
    	return $this->ativo;
    }
    
    /**
     * set ativo
     * @param Type $value
     *
     */
    public function setAtivo($value) {
    	$this->ativo = $value;
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
     * get log
     *
     */
    public function getLog() {
    	return $this->log;
    }
    
    /**
     * set log
     * @param Type $value
     *
     */
    public function setLog($value) {
    	$this->log = $value;
    }
    /**
     * get notificacoes
     *
     */
    public function getNotificacoes() {
    	return $this->notificacoes;
    }
    
    /**
     * set notificacoes
     * @param Type $value
     *
     */
    public function setNotificacoes($value) {
    	$this->notificacoes = $value;
    }
    /**
     * get questionariousados
     *
     */
    public function getQuestionariousados() {
    	return $this->questionariousados;
    }
    
    /**
     * set questionariousados
     * @param Type $value
     *
     */
    public function setQuestionariousados($value) {
    	$this->questionariousados = $value;
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
        $this->metadata()->addField('descricao', 'descricao', 'varchar', 255, array());
        $this->metadata()->addField('inicio', 'inicio', 'datetime', null, array());
        $this->metadata()->addField('fim', 'fim', 'datetime', null, array());
        $this->metadata()->addField('dataCriacao', 'data_criacao', 'datetime', null, array());
        $this->metadata()->addField('avaliado', 'avaliado', 'varchar', 45, array());
        $this->metadata()->addField('ativo', 'ativo', 'varchar', 45, array());

        
        $this->metadata()->addRelation('avaliacoes', Lumine_Metadata::ONE_TO_MANY, 'Avaliacao', 'processoAvaliacaoId', null, null, null);
        $this->metadata()->addRelation('log', Lumine_Metadata::ONE_TO_MANY, 'Log', 'processoAvaliacaoId', null, null, null);
        $this->metadata()->addRelation('notificacoes', Lumine_Metadata::ONE_TO_MANY, 'Notificacao', 'processoAvaliacaoId', null, null, null);
        $this->metadata()->addRelation('questionariousados', Lumine_Metadata::ONE_TO_MANY, 'QuestionarioUsado', 'processoAvaliacaoId', null, null, null);
    }

    #### END AUTOCODE


}
