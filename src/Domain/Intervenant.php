<?php
namespace WF3\Domain;



class Intervenant {
    private $id;
    private $logo;
    private $nom;
    private $phone;


    public function getId(){
        return $this->id;
    }
    public function setId($id){
        if(!empty($id) AND is_numeric($id)){
            $this->id = $id;
        }
        return false;
    }


    public function getLogo(){
        return $this->logo;
    }
    public function setLogo($logo){

            $this->logo = $logo;
        }

    public function getNom(){
        return $this->nom;
    }
    public function setNom($nom){

            $this->nom = $nom;
        }

    public function getPhone(){
        return $this->phone;
    }
    public function setPhone($phone){

            $this->phone = $phone;
        }


}
