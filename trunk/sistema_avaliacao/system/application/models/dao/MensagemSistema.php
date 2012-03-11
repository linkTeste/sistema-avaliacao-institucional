<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "mensagem_sistema"
 * in 2012-03-10
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class MensagemSistema extends Lumine_Base {

    
    public $id;
    public $texto;
    public $tipo;
    
    
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
     * get texto
     *
     */
    public function getTexto() {
    	return $this->texto;
    }
    
    /**
     * set texto
     * @param Type $value
     *
     */
    public function setTexto($value) {
    	$this->texto = $value;
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
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('mensagem_sistema');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('texto', 'texto', 'text', 65535, array());
        $this->metadata()->addField('tipo', 'tipo', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
