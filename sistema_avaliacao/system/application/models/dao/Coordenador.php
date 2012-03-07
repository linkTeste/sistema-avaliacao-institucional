<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "coordenador"
 * in 2012-03-06
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Coordenador extends Lumine_Base {

    
    public $id;
    public $nome;
    public $login;
    public $senha;
    public $email;
    public $avaliacoes = array();
    public $comentarios = array();
    public $cursos = array();
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
        $this->metadata()->setTablename('coordenador');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 255, array());
        $this->metadata()->addField('login', 'login', 'varchar', 255, array());
        $this->metadata()->addField('senha', 'senha', 'varchar', 255, array());
        $this->metadata()->addField('email', 'email', 'varchar', 255, array());

        
        $this->metadata()->addRelation('avaliacoes', Lumine_Metadata::ONE_TO_MANY, 'Avaliacao', 'coordenadorId', null, null, null);
        $this->metadata()->addRelation('comentarios', Lumine_Metadata::ONE_TO_MANY, 'Comentarios', 'coordenadorId', null, null, null);
        $this->metadata()->addRelation('cursos', Lumine_Metadata::ONE_TO_MANY, 'Curso', 'coordenadorId', null, null, null);
        $this->metadata()->addRelation('turmas', Lumine_Metadata::ONE_TO_MANY, 'Turma', 'coordenadorId', null, null, null);
    }

    #### END AUTOCODE


}
