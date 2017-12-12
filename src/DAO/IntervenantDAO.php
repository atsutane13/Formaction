<?php

namespace WF3\DAO;


use WF3\Domain\Intervenant;

class IntervenantDAO extends DAO {
    public function getIntervenantWithId(){
        $interId=$this->bdd->query('SELECT id, nom FROM '.$this->tableName);
        return $dropInter=$interId->fetchALL(\PDO::FETCH_ASSOC);
    }
}

   