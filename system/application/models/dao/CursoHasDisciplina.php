<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "curso_has_disciplina"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class CursoHasDisciplina extends Lumine_Base {

    
    public $cursoId;
    public $disciplinaId;
    
    
    /**
     * get cursoId
     *
     */
    public function getCursoId() {
    	return $this->cursoId;
    }
    
    /**
     * set cursoId
     * @param Type $value
     *
     */
    public function setCursoId($value) {
    	$this->cursoId = $value;
    }
    /**
     * get disciplinaId
     *
     */
    public function getDisciplinaId() {
    	return $this->disciplinaId;
    }
    
    /**
     * set disciplinaId
     * @param Type $value
     *
     */
    public function setDisciplinaId($value) {
    	$this->disciplinaId = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('curso_has_disciplina');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('cursoId', 'curso_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Curso'));
        $this->metadata()->addField('disciplinaId', 'disciplina_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Disciplina'));

        
    }

    #### END AUTOCODE


}
