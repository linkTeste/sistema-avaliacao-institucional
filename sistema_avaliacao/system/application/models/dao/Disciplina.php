<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "disciplina"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Disciplina extends Lumine_Base {

    
    public $id;
    public $nome;
    public $cursohasdisciplinas = array();
    public $professores = array();
    public $cursos = array();
    
    
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
     * get cursohasdisciplinas
     *
     */
    public function getCursohasdisciplinas() {
    	return $this->cursohasdisciplinas;
    }
    
    /**
     * set cursohasdisciplinas
     * @param Type $value
     *
     */
    public function setCursohasdisciplinas($value) {
    	$this->cursohasdisciplinas = $value;
    }
    /**
     * get professores
     *
     */
    public function getProfessores() {
    	return $this->professores;
    }
    
    /**
     * set professores
     * @param Type $value
     *
     */
    public function setProfessores($value) {
    	$this->professores = $value;
    }
    /**
     * get cursos
     *
     */
    public function getCursos() {
    	return $this->cursos;
    }
    
    /**
     * set cursos
     * @param Type $value
     *
     */
    public function setCursos($value) {
    	$this->cursos = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('disciplina');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 45, array());

        
        $this->metadata()->addRelation('cursohasdisciplinas', Lumine_Metadata::ONE_TO_MANY, 'CursoHasDisciplina', 'disciplinaId', null, null, null);
        $this->metadata()->addRelation('professores', Lumine_Metadata::ONE_TO_MANY, 'Professor', 'disciplinaId', null, null, null);
        $this->metadata()->addRelation('cursos', Lumine_Metadata::MANY_TO_MANY, 'Curso', 'id', 'curso_has_disciplina', 'disciplina_id', null);
    }

    #### END AUTOCODE


}
