<?php
namespace WF3\Domain;

class Category{

    private $id;
    private $category;

    public function getId(){
        return $this->id;
    }
    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
            return $this;
        }
        return false;
    }

    public function getCategory(){
        return $this->id;
    }
    public function setCategory(){
        if(!empty($category) AND is_string($category)){
            $this->id = $id;
            return $this;
        }
        return false;
    }
}