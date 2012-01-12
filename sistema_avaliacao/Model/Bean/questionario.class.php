<?php
class questionario {
    private $id;
    private $descricao;
    private $instrumento_id;

    public function setId($id){
        $this->id = $id;
    }

    public function getId(){
        return $this->id;
    }

    public function setDescricao($descricao){
        $this->descricao = $descricao;
    }

    public function getDescricao(){
        return $this->descricao;
    }

    public function setInstrumento_id($instrumento_id){
        $this->instrumento_id = $instrumento_id;
    }

    public function getInstrumento_id(){
        return $this->instrumento_id;
    }
}

?>