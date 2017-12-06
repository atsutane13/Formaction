<?php
namespace WF3\Domain;

class Article{
    //déclaration des attributs
    private $id;
    private $intervenantId;
    private $title;
    private $datePubli;
    private $duree;
    private $categoryId;
    private $url;

    
    public function getId(){
        return $this->id;
    }

    public function getIntervenantId(){
        return $this->intervenantId;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getDatePubli(){
        return $this->datePubli;
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
     

        //setters
    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
        }
        return false;
    }

    public function setIntervenantId($intervenantId){
            $this->intervenantId = $intervenantId;
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
    
    
    public function setCategoryId($categoryId){
            $this->categoryId = $categoryId;
    }

    public function setUrl($url){
        if(!empty($url) AND is_string($url)){
            $this->url = $url; 
        }
    }

}