<?php
namespace WF3\Domain;

class Image{
    private $id;
    private $image_pc;
    private $image_portable;
    private $users_id;

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

    public function getImage_pc(){
        return $this->image_pc;
    }
    public function setImage_pct($image_pc){
        if(!empty($image_pc) AND is_string($image_pc)){
            $this->image_pc = $image_pc; 
        }
    }

    public function getImage_portable(){
        return $this->image_portable;
    }
    public function setImage_portable($image_portable){
        if(!empty($image_portable) AND is_string($image_portable)){
            $this->image_portable = $image_portable; 
        }
    }

    public function getUsers_id(){
        return $this->users_id;
    }
    public function setUsers_id($users_id){
        
            $this->users_id = $users_id; 
        
    }
}

