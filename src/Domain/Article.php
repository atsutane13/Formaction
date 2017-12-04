<?php
namespace WF3\Domain;

class Article{
    //déclaration des attributs
    private $id;
    private $title;
    private $content;
    private $date_publi;
    private $users_id;
    private $category_id;
    private $publi;

    
    public function getId(){
        return $this->id;
    }
    
    public function getTitle(){
        return $this->title;
    }
    
    public function getContent(){
        return $this->content;
    }
    
    public function getDate_publi(){
        return $this->date_publi;
    }
    
    public function getUsers_id(){
        return $this->users_id;
    }

    public function getCategory_id(){
        return $this->category_id;
    }

    public function getPubli(){
        return $this->publi;
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

    public function setDate_publi($datePubli){
        if(!empty($datePubli) AND is_string($datePubli)){
            $this->date_publi = $datePubli; 
        }
    }

    public function setUsers_id($users_id){
        
            $this->users_id = $users_id; 
        
    }

    public function setCategory_id($category_id){
        if(!empty($category_id) AND is_numeric($category_id)){
            $this->category_id = $category_idid;
            return $this;
        }
        return false;
    }

    public function setPubli($publi){
        if(!empty($publi) AND is_string($publi)){
            $this->publi = $publi; 
        }
    }
    
}