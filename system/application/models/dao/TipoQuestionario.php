<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "tipo_questionario"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class TipoQuestionario extends Lumine_Base {

    
    public $id;
    public $nome;
    public $questionarios = array();
    
    
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
     * get questionarios
     *
     */
    public function getQuestionarios() {
    	return $this->questionarios;
    }
    
    /**
     * set questionarios
     * @param Type $value
     *
     */
    public function setQuestionarios($value) {
    	$this->questionarios = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('tipo_questionario');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 45, array());

        
        $this->metadata()->addRelation('questionarios', Lumine_Metadata::ONE_TO_MANY, 'Questionario', 'tipoQuestionarioId', null, null, null);
    }

    #### END AUTOCODE


}
