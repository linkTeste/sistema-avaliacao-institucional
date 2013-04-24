<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "usuario_has_permissao"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class UsuarioHasPermissao extends Lumine_Base {

    
    public $usuarioId;
    public $permissaoId;
    
    
    /**
     * get usuarioId
     *
     */
    public function getUsuarioId() {
    	return $this->usuarioId;
    }
    
    /**
     * set usuarioId
     * @param Type $value
     *
     */
    public function setUsuarioId($value) {
    	$this->usuarioId = $value;
    }
    /**
     * get permissaoId
     *
     */
    public function getPermissaoId() {
    	return $this->permissaoId;
    }
    
    /**
     * set permissaoId
     * @param Type $value
     *
     */
    public function setPermissaoId($value) {
    	$this->permissaoId = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('usuario_has_permissao');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('usuarioId', 'usuario_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Usuario'));
        $this->metadata()->addField('permissaoId', 'permissao_id', 'int', 11, array('primary' => true, 'notnull' => true, 'foreign' => '1', 'onUpdate' => 'CASCADE', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Permissao'));

        
    }

    #### END AUTOCODE


}
