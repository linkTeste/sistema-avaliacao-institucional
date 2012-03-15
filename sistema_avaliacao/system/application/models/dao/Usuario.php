<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "usuario"
 * in 2012-03-13
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Usuario extends Lumine_Base {

    
    public $id;
    public $nome;
    public $login;
    public $senha;
    public $email;
    public $dataCriacao;
    public $usuariohaspermissoes = array();
    public $permissoes = array();
    
    
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
     * get dataCriacao
     *
     */
    public function getDataCriacao() {
    	return $this->dataCriacao;
    }
    
    /**
     * set dataCriacao
     * @param Type $value
     *
     */
    public function setDataCriacao($value) {
    	$this->dataCriacao = $value;
    }
    /**
     * get usuariohaspermissoes
     *
     */
    public function getUsuariohaspermissoes() {
    	return $this->usuariohaspermissoes;
    }
    
    /**
     * set usuariohaspermissoes
     * @param Type $value
     *
     */
    public function setUsuariohaspermissoes($value) {
    	$this->usuariohaspermissoes = $value;
    }
    /**
     * get permissoes
     *
     */
    public function getPermissoes() {
    	return $this->permissoes;
    }
    
    /**
     * set permissoes
     * @param Type $value
     *
     */
    public function setPermissoes($value) {
    	$this->permissoes = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('usuario');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 255, array());
        $this->metadata()->addField('login', 'login', 'varchar', 45, array());
        $this->metadata()->addField('senha', 'senha', 'varchar', 255, array());
        $this->metadata()->addField('email', 'email', 'varchar', 255, array());
        $this->metadata()->addField('dataCriacao', 'data_criacao', 'datetime', null, array());

        
        $this->metadata()->addRelation('usuariohaspermissoes', Lumine_Metadata::ONE_TO_MANY, 'UsuarioHasPermissao', 'usuarioId', null, null, null);
        $this->metadata()->addRelation('permissoes', Lumine_Metadata::MANY_TO_MANY, 'Permissao', 'id', 'usuario_has_permissao', 'usuario_id', null);
    }

    #### END AUTOCODE


}
