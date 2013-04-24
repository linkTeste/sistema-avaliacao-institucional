<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "laboratorio"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Laboratorio extends Lumine_Base {

    
    public $id;
    public $nome;
    public $turmahaslaboratorios = array();
    public $turmas = array();
    
    
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
     * get turmahaslaboratorios
     *
     */
    public function getTurmahaslaboratorios() {
    	return $this->turmahaslaboratorios;
    }
    
    /**
     * set turmahaslaboratorios
     * @param Type $value
     *
     */
    public function setTurmahaslaboratorios($value) {
    	$this->turmahaslaboratorios = $value;
    }
    /**
     * get turmas
     *
     */
    public function getTurmas() {
    	return $this->turmas;
    }
    
    /**
     * set turmas
     * @param Type $value
     *
     */
    public function setTurmas($value) {
    	$this->turmas = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('laboratorio');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 100, array());

        
        $this->metadata()->addRelation('turmahaslaboratorios', Lumine_Metadata::ONE_TO_MANY, 'TurmaHasLaboratorio', 'laboratorioId', null, null, null);
        $this->metadata()->addRelation('turmas', Lumine_Metadata::MANY_TO_MANY, 'Turma', 'id', 'turma_has_laboratorio', 'laboratorio_id', null);
    }

    #### END AUTOCODE


}
