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
		$idAuteur=$article->getUsersId();
		$author=$this->userDAO->find($article->getUsers_id());
		if(array_key_exists('usersId',$row) && is_numeric($row['usersId'])){
			$auteur=$this->userDAO->find($idAuteur);
		}
		$article->setUsersId($author);

		$imageAuthor=$article->getImage();
		$image=$this->imageDAO->find($article->getImage());
		if(array_key_exists('imageId',$row) && is_string($row['imageId'])){
			$image=$this->imageDAO->find($imageAuthor);
		}
		$article->setImage($image);

		return $article;
    }


	public function getLastArticles(){
		$result = $this->bdd->query('SELECT * FROM articles ORDER BY datePubli DESC LIMIT 0,5');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	//retourne la liste des articles de l'utilisateur dont l'id est fourni
	public function getArticlesFromUser($idUser){
		$result = $this->bdd->prepare('SELECT * FROM articles WHERE usersId = :id');
		$result->bindValue(':id', $idUser, \PDO::PARAM_INT);
		$result->execute();
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function getArticlesWithAuthor(){
		$result = $this->bdd->query('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username, datePubli FROM articles INNER JOIN users ON articles.usersId = users.id');
		return $result->fetchALL(\PDO::FETCH_ASSOC);
	}
    
    public function findArticlesByTitle($title){
        $result = $this->bdd->prepare('SELECT articles.id AS idArticle, title, content, users.id AS idUser, username FROM articles INNER JOIN users ON articles.usersId = users.id WHERE title LIKE :title');
        $result->bindValue(':title', '%' . $title . '%');
        $result->execute();
        return $result->fetchALL(\PDO::FETCH_ASSOC);
	}

	public function deleteArticleByAuthor($id){
		if(!empty($id) && is_numeric($id)){
            $delete = $this->bdd->prepare('DELETE FROM '.$this->tableName.' WHERE usersId = :auteur');
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