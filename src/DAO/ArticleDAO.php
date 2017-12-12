<?php
namespace WF3\DAO;

class ArticleDAO extends DAO{

	private $intervenantDAO;


	public function setIntervenantDAO(IntervenantDAO $intervenantDAO	){
		$this->intervenantDAO=$intervenantDAO;
	}


	public function buildObject(array $row){
		$article=parent::buildObject($row);
		$idAuteur=$article->getIntervenantId();
		$author=$this->intervenantDAO->find($article->getIntervenantId());
		if(array_key_exists('intervenantId',$row) && is_numeric($row['intervenantId'])){
			$auteur=$this->intervenantDAO->find($idAuteur);
		}
		$article->setIntervenantId($author);

		return $article;
	}
	



	public function getLastArticles(){
		$result = $this->bdd->query('SELECT * FROM articles ORDER BY datePubli DESC LIMIT 0,5');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	//retourne la liste des articles de l'utilisateur dont l'id est fourni
	public function getArticlesFromUser($idUser){
		$result = $this->bdd->prepare('SELECT * FROM articles WHERE intervenantId = :id');
		$result->bindValue(':id', $idUser, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function getArticlesWithAuthor(){
		$result = $this->bdd->query('SELECT articles.id AS idArticle, title,  intervenant.id AS idIntervenant, nom, datePubli, url FROM articles INNER JOIN intervenant ON articles.intervenantId= intervenant.id');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
    
    public function findArticlesByTitle($title){
        $result = $this->bdd->prepare('SELECT articles.id AS idArticle, title, intervenant.id AS idIntervenant, nom FROM articles INNER JOIN intervenant ON articles.intervenantId = intervenant.id WHERE title LIKE :title');
        $result->bindValue(':title', '%' . $title . '%');
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function deleteArticleByAuthor($id){
		if(!empty($id) && is_numeric($id)){
            $delete = $this->bdd->prepare('DELETE FROM '.$this->tableName.' WHERE intervenantId = :id');
            $delete->bindValue(':id', $id, \PDO::PARAM_INT);

            if($delete->execute()){
                return true;
            }
        }
	}

	

	public function findArticlesByCategory($title){
        $result = $this->bdd->prepare('SELECT articles.id AS idArticle, title, intervenant.id AS idIntervenant, nom, logo FROM articles INNER JOIN intervenant ON articles.intervenantId = intervenant.id WHERE categoryId = :category');
        $result->bindValue(':category', $title);
        $result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
		
	}

	public function advanceSearch($str){
		if(!empty($str)){			
			$search = $this->bdd->prepare('SELECT articles.id , title FROM ' . $this->tableName . ' INNER JOIN intervenant ON  articles.intervenantId = intervenant.id WHERE nom LIKE :string  OR duree LIKE :string');
			$search->bindValue(':string', '%'.$str.'%');
            $search->execute();				
			return $search->fetchALL(\PDO::FETCH_ASSOC);;
            
        }
	}
	
	public function getIntervenantId($id){
		$interId=$this->bdd->prepare('SELECT intervenantId FROM '.$this->tableName.' WHERE id=:id');
		$interId->bindValue(':id', $id, \PDO::PARAM_INT);
        return $interId->fetch(\PDO::FETCH_ASSOC);

    }

}