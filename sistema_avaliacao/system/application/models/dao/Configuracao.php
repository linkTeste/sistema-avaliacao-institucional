<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "configuracao"
 * in 2012-02-16
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Configuracao extends Lumine_Base {

    
    public $id;
    public $emailToPendentes;
    public $emailInicio;
    public $emailRelatorios;
    
    
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
     * get emailToPendentes
     *
     */
    public function getEmailToPendentes() {
    	return $this->emailToPendentes;
    }
    
    /**
     * set emailToPendentes
     * @param Type $value
     *
     */
    public function setEmailToPendentes($value) {
    	$this->emailToPendentes = $value;
    }
    /**
     * get emailInicio
     *
     */
    public function getEmailInicio() {
    	return $this->emailInicio;
    }
    
    /**
     * set emailInicio
     * @param Type $value
     *
     */
    public function setEmailInicio($value) {
    	$this->emailInicio = $value;
    }
    /**
     * get emailRelatorios
     *
     */
    public function getEmailRelatorios() {
    	return $this->emailRelatorios;
    }
    
    /**
     * set emailRelatorios
     * @param Type $value
     *
     */
    public function setEmailRelatorios($value) {
    	$this->emailRelatorios = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('configuracao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('emailToPendentes', 'email_to_pendentes', 'varchar', 45, array());
        $this->metadata()->addField('emailInicio', 'email_inicio', 'varchar', 45, array());
        $this->metadata()->addField('emailRelatorios', 'email_relatorios', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
