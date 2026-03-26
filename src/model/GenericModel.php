<?php
namespace model;
use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class GenericModel{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected $id;

    public function setID($id){
        $this->id = $id;
    }
    public function getID(){
        return $this->id;
    }



}

?>
