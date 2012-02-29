<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "coordenador"
 * in 2012-02-29
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Coordenador extends Lumine_Base {

    
    public $id;
    public $usuarioId;
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
        $this->metadata()->setTablename('coordenador');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true));
        $this->metadata()->addField('usuarioId', 'usuario_id', 'int', 11, array('notnull' => true, 'foreign' => '1', 'onUpdate' => 'RESTRICT', 'onDelete' => 'RESTRICT', 'linkOn' => 'id', 'class' => 'Usuario'));

        
        $this->metadata()->addRelation('cursos', Lumine_Metadata::ONE_TO_MANY, 'Curso', 'coordenadorId', null, null, null);
    }

    #### END AUTOCODE


}
