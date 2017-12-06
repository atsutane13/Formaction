<?php
namespace WF3\Domain;

class Article{
    //déclaration des attributs
    private $id;
    private $intervenant;
    private $title;
    private $datePubli;
    private $usersId;
    private $duree;
    private $categoryId;
    private $image;
    private $url;

    
    public function getId(){
        return $this->id;
    }

    public function getIntervenant(){
        return $this->intervenant;
    }
    
    public function getTitle(){
        return $this->title;
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

    public function getUrl(){
        return $this->url;
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

    public function setIntervenant($intervenant){
        if(!empty($intervenant) AND is_numeric($intervenant)){
            $this->intervenant = $intervenant;
            return $this;
        }
        return false;
    }

    public function setTitle($title){
        if(!empty($title) AND is_string($title)){
            $this->title = $title; 
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

    public function setUrl($url){
        if(!empty($url) AND is_string($url)){
            $this->url = $url; 
        }
    }

    public function setImage($image_pc){
        if(!empty($image_pc) AND is_string($image_pc)){
            $this->image = $image_pc; 
        }
    }
}