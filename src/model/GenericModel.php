<?php
namespace model;
abstract class GenericModel{
    private $id;

    public function setID($id){
        $this->id = $id;
    }
    public function getID(){
        return $this->id;
    }



}

?>
