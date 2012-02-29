<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "aluno"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Aluno extends Lumine_Base {

    
    public $ra;
    public $nome;
    public $login;
    public $senha;
    public $email;
    public $curso;
    public $sitAcademica;
    public $avaliacoes = array();
    public $comentarios = array();
    public $turmahasalunos = array();
    public $turmas = array();
    
    
    /**
     * get ra
     *
     */
    public function getRa() {
    	return $this->ra;
    }
    
    /**
     * set ra
     * @param Type $value
     *
     */
    public function setRa($value) {
    	$this->ra = $value;
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
     * get login
     *
     */
    public function getLogin() {
    	return $this->login;
    }
    
    /**
     * set login
     * @param Type $value
     *
     */
    public function setLogin($value) {
    	$this->login = $value;
    }
    /**
     * get senha
     *
     */
    public function getSenha() {
    	return $this->senha;
    }
    
    /**
     * set senha
     * @param Type $value
     *
     */
    public function setSenha($value) {
    	$this->senha = $value;
    }
    /**
     * get email
     *
     */
    public function getEmail() {
    	return $this->email;
    }
    
    /**
     * set email
     * @param Type $value
     *
     */
    public function setEmail($value) {
    	$this->email = $value;
    }
    /**
     * get curso
     *
     */
    public function getCurso() {
    	return $this->curso;
    }
    
    /**
     * set curso
     * @param Type $value
     *
     */
    public function setCurso($value) {
    	$this->curso = $value;
    }
    /**
     * get sitAcademica
     *
     */
    public function getSitAcademica() {
    	return $this->sitAcademica;
    }
    
    /**
     * set sitAcademica
     * @param Type $value
     *
     */
    public function setSitAcademica($value) {
    	$this->sitAcademica = $value;
    }
    /**
     * get avaliacoes
     *
     */
    public function getAvaliacoes() {
    	return $this->avaliacoes;
    }
    
    /**
     * set avaliacoes
     * @param Type $value
     *
     */
    public function setAvaliacoes($value) {
    	$this->avaliacoes = $value;
    }
    /**
     * get comentarios
     *
     */
    public function getComentarios() {
    	return $this->comentarios;
    }
    
    /**
     * set comentarios
     * @param Type $value
     *
     */
    public function setComentarios($value) {
    	$this->comentarios = $value;
    }
    /**
     * get turmahasalunos
     *
     */
    public function getTurmahasalunos() {
    	return $this->turmahasalunos;
    }
    
    /**
     * set turmahasalunos
     * @param Type $value
     *
     */
    public function setTurmahasalunos($value) {
    	$this->turmahasalunos = $value;
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
        $this->metadata()->setTablename('aluno');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('ra', 'ra', 'varchar', 45, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 255, array());
        $this->metadata()->addField('login', 'login', 'varchar', 45, array());
        $this->metadata()->addField('senha', 'senha', 'varchar', 255, array());
        $this->metadata()->addField('email', 'email', 'varchar', 255, array());
        $this->metadata()->addField('curso', 'curso', 'varchar', 255, array());
        $this->metadata()->addField('sitAcademica', 'sit_academica', 'int', 11, array());

        
        $this->metadata()->addRelation('avaliacoes', Lumine_Metadata::ONE_TO_MANY, 'Avaliacao', 'alunoRa', null, null, null);
        $this->metadata()->addRelation('comentarios', Lumine_Metadata::ONE_TO_MANY, 'Comentarios', 'alunoRa', null, null, null);
        $this->metadata()->addRelation('turmahasalunos', Lumine_Metadata::ONE_TO_MANY, 'TurmaHasAluno', 'alunoRa', null, null, null);
        $this->metadata()->addRelation('turmas', Lumine_Metadata::MANY_TO_MANY, 'Turma', 'ra', 'turma_has_aluno', 'aluno_ra', null);
    }

    #### END AUTOCODE


}
