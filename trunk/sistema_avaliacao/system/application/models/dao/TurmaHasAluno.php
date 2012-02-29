<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "turma_has_aluno"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class TurmaHasAluno extends Lumine_Base {

    
    public $turmaIdTurma;
    public $alunoRa;
    public $avaliado;
    
    
    /**
     * get turmaIdTurma
     *
     */
    public function getTurmaIdTurma() {
    	return $this->turmaIdTurma;
    }
    
    /**
     * set turmaIdTurma
     * @param Type $value
     *
     */
    public function setTurmaIdTurma($value) {
    	$this->turmaIdTurma = $value;
    }
    /**
     * get alunoRa
     *
     */
    public function getAlunoRa() {
    	return $this->alunoRa;
    }
    
    /**
     * set alunoRa
     * @param Type $value
     *
     */
    public function setAlunoRa($value) {
    	$this->alunoRa = $value;
    }
    /**
     * get avaliado
     *
     */
    public function getAvaliado() {
    	return $this->avaliado;
    }
    
    /**
     * set avaliado
     * @param Type $value
     *
     */
    public function setAvaliado($value) {
    	$this->avaliado = $value;
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
        
        $this->metadata()->addField('turmaIdTurma', 'turma_id_turma', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'idTurma', 'class' => 'Turma'));
        $this->metadata()->addField('alunoRa', 'aluno_ra', 'varchar', 45, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'ra', 'class' => 'Aluno'));
        $this->metadata()->addField('avaliado', 'avaliado', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
