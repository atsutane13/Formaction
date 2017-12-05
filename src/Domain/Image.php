<?php
namespace WF3\Domain;

class Image{
    private $id;
    private $imagePc;
    private $imagePortable;
    private $usersId;

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

    public function getImagePc(){
        return $this->imagePc;
    }
    public function setImagePc($imagePc){
        if(!empty($imagePc) AND is_string($imagePc)){
            $this->imagePc = $imagePc; 
        }
    }

    public function getImagePortable(){
        return $this->imagePortable;
    }
    public function setImagePortable($imagePortable){
        if(!empty($imagePortable) AND is_string($imagePortable)){
            $this->imagePortable = $imagePortable; 
        }
    }

    public function getUsersId(){
        return $this->usersId;
    }
    public function setUsersId($usersId){
        
            $this->usersId = $usersId; 
        
    }
}

