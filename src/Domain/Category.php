<?php
namespace WF3\Domain;

class Category{

    private $id;
    private $category;
    private $image;

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
        return $this->category;
    }
    public function setCategory($category){
        if(!empty($category) AND is_string($category)){
            $this->category = $category;
            return $this;
        }

    }

    public function getImage(){
        return $this->image;
    }
    public function setImage($image){
            $this->image = $image;

    }
}