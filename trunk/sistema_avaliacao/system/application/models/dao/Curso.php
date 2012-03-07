<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "curso"
 * in 2012-03-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Curso extends Lumine_Base {

    
    public $nome;
    public $coordenadorId;
    
    
    /**
     * get nome
     *
     */
    public function getNome() {
    	return $this->nome;
    }
    
    /**
     * set nome
     * @param Type $value
     *
     */
    public function setNome($value) {
    	$this->nome = $value;
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
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('curso');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('nome', 'nome', 'varchar', 45, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('coordenadorId', 'coordenador_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Coordenador'));

        
    }

    #### END AUTOCODE


}
