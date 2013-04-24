<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "permissao"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Permissao extends Lumine_Base {

    
    public $id;
    public $nome;
    public $link;
    public $usuariohaspermissoes = array();
    public $usuarios = array();
    
    
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
     * get link
     *
     */
    public function getLink() {
    	return $this->link;
    }
    
    /**
     * set link
     * @param Type $value
     *
     */
    public function setLink($value) {
    	$this->link = $value;
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
     * get usuarios
     *
     */
    public function getUsuarios() {
    	return $this->usuarios;
    }
    
    /**
     * set usuarios
     * @param Type $value
     *
     */
    public function setUsuarios($value) {
    	$this->usuarios = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('permissao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('nome', 'nome', 'varchar', 45, array());
        $this->metadata()->addField('link', 'link', 'varchar', 45, array());

        
        $this->metadata()->addRelation('usuariohaspermissoes', Lumine_Metadata::ONE_TO_MANY, 'UsuarioHasPermissao', 'permissaoId', null, null, null);
        $this->metadata()->addRelation('usuarios', Lumine_Metadata::MANY_TO_MANY, 'Usuario', 'id', 'usuario_has_permissao', 'permissao_id', null);
    }

    #### END AUTOCODE


}
