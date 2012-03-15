<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "log"
 * in 2012-03-13
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Log extends Lumine_Base {

    
    public $id;
    public $usuario;
    public $operacao;
    public $hora;
    public $ip;
    
    
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
     * get usuario
     *
     */
    public function getUsuario() {
    	return $this->usuario;
    }
    
    /**
     * set usuario
     * @param Type $value
     *
     */
    public function setUsuario($value) {
    	$this->usuario = $value;
    }
    /**
     * get operacao
     *
     */
    public function getOperacao() {
    	return $this->operacao;
    }
    
    /**
     * set operacao
     * @param Type $value
     *
     */
    public function setOperacao($value) {
    	$this->operacao = $value;
    }
    /**
     * get hora
     *
     */
    public function getHora() {
    	return $this->hora;
    }
    
    /**
     * set hora
     * @param Type $value
     *
     */
    public function setHora($value) {
    	$this->hora = $value;
    }
    /**
     * get ip
     *
     */
    public function getIp() {
    	return $this->ip;
    }
    
    /**
     * set ip
     * @param Type $value
     *
     */
    public function setIp($value) {
    	$this->ip = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('log');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('usuario', 'usuario', 'varchar', 45, array());
        $this->metadata()->addField('operacao', 'operacao', 'varchar', 255, array());
        $this->metadata()->addField('hora', 'hora', 'datetime', null, array());
        $this->metadata()->addField('ip', 'ip', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
