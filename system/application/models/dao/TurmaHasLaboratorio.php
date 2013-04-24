<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "turma_has_laboratorio"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class TurmaHasLaboratorio extends Lumine_Base {

    
    public $turmaIdTurma;
    public $laboratorioId;
    
    
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
     * get laboratorioId
     *
     */
    public function getLaboratorioId() {
    	return $this->laboratorioId;
    }
    
    /**
     * set laboratorioId
     * @param Type $value
     *
     */
    public function setLaboratorioId($value) {
    	$this->laboratorioId = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('turma_has_laboratorio');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('turmaIdTurma', 'turma_id_turma', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'idTurma', 'class' => 'Turma'));
        $this->metadata()->addField('laboratorioId', 'laboratorio_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Laboratorio'));

        
    }

    #### END AUTOCODE


}
