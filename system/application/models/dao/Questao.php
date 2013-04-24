<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "questao"
 * in 2012-06-20
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
    public $dataCriacao;
    public $tipo;
    public $subtipo;
    public $questionariohasquestoes = array();
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
     * get questionariohasquestoes
     *
     */
    public function getQuestionariohasquestoes() {
    	return $this->questionariohasquestoes;
    }
    
    /**
     * set questionariohasquestoes
     * @param Type $value
     *
     */
    public function setQuestionariohasquestoes($value) {
    	$this->questionariohasquestoes = $value;
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
        $this->metadata()->addField('dataCriacao', 'data_criacao', 'datetime', null, array());
        $this->metadata()->addField('tipo', 'tipo', 'varchar', 45, array());
        $this->metadata()->addField('subtipo', 'subtipo', 'varchar', 45, array());

        
        $this->metadata()->addRelation('questionariohasquestoes', Lumine_Metadata::ONE_TO_MANY, 'QuestionarioHasQuestao', 'questaoId', null, null, null);
        $this->metadata()->addRelation('questionarios', Lumine_Metadata::MANY_TO_MANY, 'Questionario', 'id', 'questionario_has_questao', 'questao_id', null);
    }

    #### END AUTOCODE


}
