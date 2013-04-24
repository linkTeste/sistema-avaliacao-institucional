<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "aluno_cursa_disciplina"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class AlunoCursaDisciplina extends Lumine_Base {

    
    public $alunoId;
    public $cursoHasDisciplinaCursoId;
    public $cursoHasDisciplinaDisciplinaId;
    
    
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
     * get cursoHasDisciplinaCursoId
     *
     */
    public function getCursoHasDisciplinaCursoId() {
    	return $this->cursoHasDisciplinaCursoId;
    }
    
    /**
     * set cursoHasDisciplinaCursoId
     * @param Type $value
     *
     */
    public function setCursoHasDisciplinaCursoId($value) {
    	$this->cursoHasDisciplinaCursoId = $value;
    }
    /**
     * get cursoHasDisciplinaDisciplinaId
     *
     */
    public function getCursoHasDisciplinaDisciplinaId() {
    	return $this->cursoHasDisciplinaDisciplinaId;
    }
    
    /**
     * set cursoHasDisciplinaDisciplinaId
     * @param Type $value
     *
     */
    public function setCursoHasDisciplinaDisciplinaId($value) {
    	$this->cursoHasDisciplinaDisciplinaId = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('aluno_cursa_disciplina');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('alunoId', 'aluno_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Aluno'));
        $this->metadata()->addField('cursoHasDisciplinaCursoId', 'curso_has_disciplina_curso_id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('cursoHasDisciplinaDisciplinaId', 'curso_has_disciplina_disciplina_id', 'int', 11, array('primary' => true, 'notnull' => true));

        
    }

    #### END AUTOCODE


}
