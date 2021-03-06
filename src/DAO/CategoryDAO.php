<?php

namespace WF3\DAO;


use WF3\Domain\Category;

class CategoryDAO extends DAO {

    public function DeleteCategory($id){
		if(!empty($id) && is_numeric($id)){
            $delete = $this->bdd->prepare('DELETE FROM '.$this->tableName.' WHERE id = :id');
            $delete->bindValue(':id', $id, \PDO::PARAM_INT);

            if($delete->execute()){
                return true;
            }
        }
    }
    
    public function getCategoryWithId(){
        $catId=$this->bdd->query('SELECT id, category FROM '.$this->tableName);
        return $dropCat=$catId->fetchALL(\PDO::FETCH_ASSOC);
    }

}