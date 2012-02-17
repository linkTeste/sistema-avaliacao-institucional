<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "questao"
 * in 2012-02-17
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Questao extends Lumine_Base {

    
    public $id;
    public $texto;
    public $topico;
    public $opcional;
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
     * get topico
     *
     */
    public function getTopico() {
    	return $this->topico;
    }
    
    /**
     * set topico
     * @param Type $value
     *
     */
    public function setTopico($value) {
    	$this->topico = $value;
    }
    /**
     * get opcional
     *
     */
    public function getOpcional() {
    	return $this->opcional;
    }
    
    /**
     * set opcional
     * @param Type $value
     *
     */
    public function setOpcional($value) {
    	$this->opcional = $value;
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
        $this->metadata()->setTablename('questao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('texto', 'texto', 'varchar', 255, array());
        $this->metadata()->addField('topico', 'topico', 'varchar', 45, array());
        $this->metadata()->addField('opcional', 'opcional', 'varchar', 45, array());

        
        $this->metadata()->addRelation('questionarios', Lumine_Metadata::MANY_TO_MANY, 'Questionario', 'id', 'questionario_has_questao', 'questao_id', null);
    }

    #### END AUTOCODE


}
