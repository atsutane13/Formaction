<?php
namespace WF3\DAO;

class ArticleDAO extends DAO{

	private $userDAO;
	private $imageDAO;

	public function setUserDAO(UserDAO $userDAO	){
		$this->userDAO=$userDAO;
	}

	public function setImageDAO(ImageDAO $imageDAO	){
		$this->imageDAO=$imageDAO;
	}

	public function buildObject(array $row){
		$article=parent::buildObject($row);
		$idAuteur=$article->getAuthor();
		$author=$this->userDAO->find($article->getAuthor());
		if(array_key_exists('users_id',$row) && is_numeric($row['users_id'])){
			$auteur=$this->userDAO->find($idAuteur);
		}
		$article->setAuthor($author);
		$imageAuthor=$article->getImage();
		$image=$this->imageDAO->find($article->getImage());
		if(array_key_exists('users_id',$row) && is_string($row['users_id'])){
			$image=$this->userDAO->find($imageAuthor);
		}
		$article->setAuthor($image);

		return $article;
    }


	public function getLastArticles(){
		$result = $this->bdd->query('SELECT * FROM articles ORDER BY date_publi DESC LIMIT 0,5');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	//retourne la liste des articles de l'utilisateur dont l'id est fourni
	public function getArticlesFromUser($idUser){
		$result = $this->bdd->prepare('SELECT * FROM articles WHERE users_id = :id');
		$result->bindValue(':id', $idUser, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function getArticlesWithAuthor(){
		$result = $this->bdd->query('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username, date_publi FROM articles INNER JOIN users ON articles.users_id = users.id');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
    
    public function findArticlesByTitle($title){
        $result = $this->bdd->prepare('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username FROM articles INNER JOIN users ON articles.users_id = users.id WHERE title LIKE :title');
        $result->bindValue(':title', '%' . $title . '%');
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function deleteArticleByAuthor($id){
		if(!empty($id) && is_numeric($id)){
            $delete = $this->bdd->prepare('DELETE FROM '.$this->tableName.' WHERE author = :auteur');
            $delete->bindValue(':auteur', $id, \PDO::PARAM_INT);

            if($delete->execute()){
                return true;
            }
        }
	}

	public function advanceSearch($str){
		if(!empty($str)){			
			$search = $this->bdd->prepare('SELECT articles.id , title FROM ' . $this->tableName . ' INNER JOIN users ON  articles.author = users.id WHERE username LIKE :string OR title LIKE :string OR content LIKE :string');
			$search->bindValue(':string', '%'.$str.'%');

            if($search->execute()){				
				$rows =$search->fetchALL(\PDO::FETCH_ASSOC);
				$articles=[];
				foreach ($rows as $row){
					$article=$this->buildObject($row);
					$articles[$row['id']] = $article;
				}
				return $articles;
            }
        }
	}
}