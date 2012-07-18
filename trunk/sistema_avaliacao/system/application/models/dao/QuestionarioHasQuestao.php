<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "questionario_has_questao"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class QuestionarioHasQuestao extends Lumine_Base {

    
    public $questionarioId;
    public $questaoId;
    public $ordem;
    
    
    /**
     * get questionarioId
     *
     */
    public function getQuestionarioId() {
    	return $this->questionarioId;
    }
    
    /**
     * set questionarioId
     * @param Type $value
     *
     */
    public function setQuestionarioId($value) {
    	$this->questionarioId = $value;
    }
    /**
     * get questaoId
     *
     */
    public function getQuestaoId() {
    	return $this->questaoId;
    }
    
    /**
     * set questaoId
     * @param Type $value
     *
     */
    public function setQuestaoId($value) {
    	$this->questaoId = $value;
    }
    /**
     * get ordem
     *
     */
    public function getOrdem() {
    	return $this->ordem;
    }
    
    /**
     * set ordem
     * @param Type $value
     *
     */
    public function setOrdem($value) {
    	$this->ordem = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('questionario_has_questao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('questionarioId', 'questionario_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Questionario'));
        $this->metadata()->addField('questaoId', 'questao_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Questao'));
        $this->metadata()->addField('ordem', 'ordem', 'int', 11, array());

        
    }

    #### END AUTOCODE


}
