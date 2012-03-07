<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "notificacao"
 * in 2012-03-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Notificacao extends Lumine_Base {

    
    public $id;
    public $avaliacaoPendente;
    public $avaliacaoInicio;
    public $avaliacaoProrrogada;
    public $avaliacaoFim;
    public $relatoriosDisponiveis;
    
    
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
     * get avaliacaoPendente
     *
     */
    public function getAvaliacaoPendente() {
    	return $this->avaliacaoPendente;
    }
    
    /**
     * set avaliacaoPendente
     * @param Type $value
     *
     */
    public function setAvaliacaoPendente($value) {
    	$this->avaliacaoPendente = $value;
    }
    /**
     * get avaliacaoInicio
     *
     */
    public function getAvaliacaoInicio() {
    	return $this->avaliacaoInicio;
    }
    
    /**
     * set avaliacaoInicio
     * @param Type $value
     *
     */
    public function setAvaliacaoInicio($value) {
    	$this->avaliacaoInicio = $value;
    }
    /**
     * get avaliacaoProrrogada
     *
     */
    public function getAvaliacaoProrrogada() {
    	return $this->avaliacaoProrrogada;
    }
    
    /**
     * set avaliacaoProrrogada
     * @param Type $value
     *
     */
    public function setAvaliacaoProrrogada($value) {
    	$this->avaliacaoProrrogada = $value;
    }
    /**
     * get avaliacaoFim
     *
     */
    public function getAvaliacaoFim() {
    	return $this->avaliacaoFim;
    }
    
    /**
     * set avaliacaoFim
     * @param Type $value
     *
     */
    public function setAvaliacaoFim($value) {
    	$this->avaliacaoFim = $value;
    }
    /**
     * get relatoriosDisponiveis
     *
     */
    public function getRelatoriosDisponiveis() {
    	return $this->relatoriosDisponiveis;
    }
    
    /**
     * set relatoriosDisponiveis
     * @param Type $value
     *
     */
    public function setRelatoriosDisponiveis($value) {
    	$this->relatoriosDisponiveis = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('notificacao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('avaliacaoPendente', 'avaliacao_pendente', 'int', 11, array());
        $this->metadata()->addField('avaliacaoInicio', 'avaliacao_inicio', 'int', 11, array());
        $this->metadata()->addField('avaliacaoProrrogada', 'avaliacao_prorrogada', 'int', 11, array());
        $this->metadata()->addField('avaliacaoFim', 'avaliacao_fim', 'int', 11, array());
        $this->metadata()->addField('relatoriosDisponiveis', 'relatorios_disponiveis', 'int', 11, array());

        
    }

    #### END AUTOCODE


}
