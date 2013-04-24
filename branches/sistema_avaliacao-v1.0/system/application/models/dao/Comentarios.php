<?php
#### START AUTOCODE
/**
 * Classe generada para a tabela "comentarios"
 * in 2012-06-20
 * @author Hugo Ferreira da Silva
 * @link http://www.hufersil.com.br/lumine
 * @package system.application.models.dao
 *
 */

class Comentarios extends Lumine_Base {

    
    public $id;
    public $comentario;
    public $dataAvaliacao;
    public $coordenadorId;
    public $itemAvaliado;
    public $avaliador;
    public $tipoAvaliacao;
    public $subtipoAvaliacao;
    
    
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
     * get comentario
     *
     */
    public function getComentario() {
    	return $this->comentario;
    }
    
    /**
     * set comentario
     * @param Type $value
     *
     */
    public function setComentario($value) {
    	$this->comentario = $value;
    }
    /**
     * get dataAvaliacao
     *
     */
    public function getDataAvaliacao() {
    	return $this->dataAvaliacao;
    }
    
    /**
     * set dataAvaliacao
     * @param Type $value
     *
     */
    public function setDataAvaliacao($value) {
    	$this->dataAvaliacao = $value;
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
     * get itemAvaliado
     *
     */
    public function getItemAvaliado() {
    	return $this->itemAvaliado;
    }
    
    /**
     * set itemAvaliado
     * @param Type $value
     *
     */
    public function setItemAvaliado($value) {
    	$this->itemAvaliado = $value;
    }
    /**
     * get avaliador
     *
     */
    public function getAvaliador() {
    	return $this->avaliador;
    }
    
    /**
     * set avaliador
     * @param Type $value
     *
     */
    public function setAvaliador($value) {
    	$this->avaliador = $value;
    }
    /**
     * get tipoAvaliacao
     *
     */
    public function getTipoAvaliacao() {
    	return $this->tipoAvaliacao;
    }
    
    /**
     * set tipoAvaliacao
     * @param Type $value
     *
     */
    public function setTipoAvaliacao($value) {
    	$this->tipoAvaliacao = $value;
    }
    /**
     * get subtipoAvaliacao
     *
     */
    public function getSubtipoAvaliacao() {
    	return $this->subtipoAvaliacao;
    }
    
    /**
     * set subtipoAvaliacao
     * @param Type $value
     *
     */
    public function setSubtipoAvaliacao($value) {
    	$this->subtipoAvaliacao = $value;
    }
    
    /**
     * Inicia os valores da classe
     * @author Hugo Ferreira da Silva
     * @return void
     */
    protected function _initialize()
    {
        $this->metadata()->setTablename('comentarios');
        $this->metadata()->setPackage('system.application.models.dao');
        
        # nome_do_membro, nome_da_coluna, tipo, comprimento, opcoes
        
        $this->metadata()->addField('id', 'id', 'int', 11, array('primary' => true, 'notnull' => true, 'autoincrement' => true));
        $this->metadata()->addField('comentario', 'comentario', 'varchar', 45, array());
        $this->metadata()->addField('dataAvaliacao', 'data_avaliacao', 'datetime', null, array());
        $this->metadata()->addField('coordenadorId', 'coordenador_id', 'int', 11, array());
        $this->metadata()->addField('itemAvaliado', 'item_avaliado', 'varchar', 45, array());
        $this->metadata()->addField('avaliador', 'avaliador', 'varchar', 45, array());
        $this->metadata()->addField('tipoAvaliacao', 'tipo_avaliacao', 'varchar', 45, array());
        $this->metadata()->addField('subtipoAvaliacao', 'subtipo_avaliacao', 'varchar', 45, array());

        
    }

    #### END AUTOCODE


}
