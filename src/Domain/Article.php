<?php
namespace WF3\Domain;

class Article{
    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $datePubli;
    private $usersId;
    private $duree;
    private $categoryId;
    private $publi;
    private $image;

    
    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getDatePubli(){
        return $this->datePubli;
    }
    
    public function getUsersId(){
        return $this->usersId;
    }
    
    public function getCategoryId(){
        return $this->categoryId;
    }
    
    public function getPubli(){
        return $this->publi;
    }
    
    public function getDuree(){
        return $this->duree;
    }
     
    public function getImage(){
        return $this->image;
    }

        //setters
    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
            return $this;
        }
        return false;
    }

    public function setTitle($title){
        if(!empty($title) AND is_string($title)){
            $this->title = $title; 
        }
    }

    public function setContent($content){
        if(!empty($content) AND is_string($content)){
            $this->content = $content; 
        }
    }
    
    public function setDatePubli($datePubli){
        if(!empty($datePubli) AND is_string($datePubli)){
            $this->datePubli = $datePubli; 
        }
    }
    
    public function setDuree($duree){
        if(!empty($duree) AND is_numeric($duree)){
            $this->duree = $duree;
        }
        return false;
    }
    
    public function setUsersId($usersId){        
        $this->usersId = $usersId;         
    }
    
    public function setCategoryId($categoryId){
        if(!empty($categoryId) AND is_numeric($categoryId)){
            $this->categoryId = $categoryId;
        }
        return false;
    }

    public function setPubli($publi){
        if(!empty($publi) AND is_string($publi)){
            $this->publi = $publi; 
        }
    }

    public function setImage($image_pc){
        if(!empty($image_pc) AND is_string($image_pc)){
            $this->image = $image_pc; 
        }
    }
}