<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "curso"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Curso extends Lumine_Base {

    
    public $id;
    public $nome;
    public $coordenadorId;
    public $cursohasdisciplinas = array();
    public $disciplinas = array();
    
    
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
     * get coordenadorId
     *
     */
    public function getCoordenadorId() {
    	return $this->coordenadorId;
    }
    
    /**
     * set coordenadorId
     * @param Type $value
     *
     */
    public function setCoordenadorId($value) {
    	$this->coordenadorId = $value;
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
     * get disciplinas
     *
     */
    public function getDisciplinas() {
    	return $this->disciplinas;
    }
    
    /**
     * set disciplinas
     * @param Type $value
     *
     */
    public function setDisciplinas($value) {
    	$this->disciplinas = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('curso');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 45, array());
        $this->metadata()->addField('coordenadorId', 'Coordenador_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Coordenador'));

        
        $this->metadata()->addRelation('cursohasdisciplinas', Lumine_Metadata::ONE_TO_MANY, 'CursoHasDisciplina', 'cursoId', null, null, null);
        $this->metadata()->addRelation('disciplinas', Lumine_Metadata::MANY_TO_MANY, 'Disciplina', 'id', 'curso_has_disciplina', 'curso_id', null);
    }

    #### END AUTOCODE


}
