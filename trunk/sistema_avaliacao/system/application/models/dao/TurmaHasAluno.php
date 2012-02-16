<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "turma_has_aluno"
 * in 2012-01-24
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class TurmaHasAluno extends Lumine_Base {

    
    public $turmaId;
    public $alunoId;
    
    
    /**
     * get turmaId
     *
     */
    public function getTurmaId() {
    	return $this->turmaId;
    }
    
    /**
     * set turmaId
     * @param Type $value
     *
     */
    public function setTurmaId($value) {
    	$this->turmaId = $value;
    }
    /**
     * get alunoId
     *
     */
    public function getAlunoId() {
    	return $this->alunoId;
    }
    
    /**
     * set alunoId
     * @param Type $value
     *
     */
    public function setAlunoId($value) {
    	$this->alunoId = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('turma_has_aluno');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('turmaId', 'turma_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'idTurma', 'class' => 'Turma'));
        $this->metadata()->addField('alunoId', 'aluno_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Aluno'));

        
    }

    #### END AUTOCODE


}
