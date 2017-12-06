<?php
namespace WF3\Controller;

use Silex\Application;
//cette ligne nous permet d'utiliser le service fourni par symfony pour gérer 
// les $_GET et $_POST
use Symfony\Component\HttpFoundation\Request;
use WF3\Domain\Article;
use WF3\Domain\User;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\ContactType;
use WF3\Form\Type\SearchType;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use WF3\Form\Type\UserRegisterType;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;
use WF3\Form\Type\UploadImageType;

class HomeController{

	//page d'accueil qui affiche tout les articles
	public function homePageAction(Application $app){
		$articles = $app['dao.article']->getArticlesWithAuthor();	
		if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}

		 return $app['twig']->render('index.html.twig', array('articles' => $articles,'token'=>$tok));
		}
		return $app['twig']->render('index.html.twig', array('articles' => $articles));
	}

	//page qui affiche les 5 derniers articles
	public function lastFiveArticlesAction(Application $app){
		$articles = $app['dao.article']->getLastArticles();
		if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}
		 return $app['twig']->render('last_articles.html.twig', array('articles' => $articles,'token'=>$tok));
		}
		return $app['twig']->render('last_articles.html.twig', array('articles' => $articles));
	}

	//page d'affichage d'un article
	public function articleAction(Application $app, $id){
		$article = $app['dao.article']->find($id);
		if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}
		return $app['twig']->render('article.html.twig', array('article' => $article,'token'=>$tok));
		}
		return $app['twig']->render('article.html.twig', array('article' => $article));
	}

	//page de suppression d'article
	public function deleteArticleAction(Application $app, $id){
		if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
		$article = $app['dao.article']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('home'));
	}

	//liste des utilisateurs
	public function usersListAction(Application $app){
		$users = $app['dao.user']->findAll();
    	return $app['twig']->render('users.list.html.twig', array('users' => $users));
	}

	//fiche d'un utilisateur
	public function userAction(Application $app,Request $request, $id){
		$user = $app['dao.user']->find($id);
	    //on va chercher la liste des articles écrits par l'utilisateur dont l'id est $id
	    //on utilise la méthode getArticlesFromUser() de la classe ArticleDAO
		$articles = $app['dao.article']->getArticlesFromUser($id);	
		if($app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}
			$uploadForm = $app['form.factory']->create(UploadImageType::class);
			$uploadForm->handleRequest($request);
			if($uploadForm->isSubmitted() AND $uploadForm->isValid()){
				$file = $request->files->get('upload_image')['image'];
				//je lui dis où stocker le fichier
				//$app['upload_dir'] est défini dans app/config/prod.php
				$path = __DIR__.'/../../'.$app['upload_dir'];
				//le nom original est dispo avec :
				//$filename = $file->getClientOriginalName();
				//guessExtension() renvoie l'extension du fichier
				$filename = md5(uniqid()).'.'.$file->guessExtension();
				//on transfère le fichier
				$file->move($path,$filename);
				
			}
		return $app['twig']->render('user.html.twig', array('user' => $user, 'articles' => $articles, 'token'=>$tok,
		'uploadForm' => $uploadForm->createView()));
		}
		return $app['twig']->render('user.html.twig', array('user' => $user, 'articles' => $articles));
	}

	//page contact
	public function contactAction(Application $app, Request $request){
		$contactForm = $app['form.factory']->create(ContactType::class);
		$contactForm->handleRequest($request);
		if($contactForm->isSubmitted() && $contactForm->isValid()){
			$data=$contactForm->getData();
			$message= \Swift_Message::newInstance()
			->setSubject($data['subject'])
			->setFrom(array('promo5wf3@gmx.fr'))//le mail correspondra a celui du formulaire
			->setTo(array('ggautier.gael@gmail.com'))//le mail utiliser sera celui donner par l'hebergeur ou au notre
			->setBody($app['twig']->render('contact.email.html.twig',array('name'=>$data['name'],'email'=>$data['email'],'message'=>$data['message'])),
			'text/html');
			$app['mailer']->send($message);
		}
		return $app['twig']->render('contact.html.twig', array('contactForm' => $contactForm->createView() ));
	}
    
    //page contact
    public function seurcheAction(Application $app, Request $request){
        $articles = $app['dao.article']->findArticlesByTitle($request->query->get('search'));
        //$articles = $app['dao.article']->findArticlesByTitle($_GET['title']);
        return $app['twig']->render('results.search.html.twig', array('articles' => $articles));
    }
    
    public function loginAction(Application $app, Request $request){//formulaire de connexion en dur.
    	//j'appelle la vue qui contient le formulaire de connexion
    	//error va contenir les éventuels messages d'erreur
    	return $app['twig']->render('login.html.twig', array(
    		'error' => $app['security.last_error']($request),
    		'last_username' => $app['session']->get('_security.last_username')
    	));
	}
	
	public function AddArticleAction(Application $app, Request $request){
        $article = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $app['dao.article']->insert($article);
            $app['session']->getFlashBag()->add('success', 'Article bien enregistré');
        }
        return $app['twig']->render('ajout.article.html.twig', array(
            'articleForm' => $articleForm->createView(),
            'article' => $article
        ));
    }

    public function ajoutArticleAction(Application $app, Request $request){
    	//on récupère l'utilisateur connecté
		if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
		$token=$app['security.token_storage']->getToken();
		$user=$token->getUser();

    	//je crée un objet article vide
		$article = new Article();
    	//je crée mon objet formulaire à partir de la classe ArticleType
    	$articleForm = $app['form.factory']->create(ArticleType::class, $article);
    	$articleForm->handleRequest($request);
    	if($articleForm->isSubmitted() && $articleForm->isValid()){
			$article->setUsersId($user->getId());	
			$path = __DIR__.'/../../'.$app['upload_dir'];
			$file = $request->files->get('article')['image'];
			
			$filename = md5(uniqid()).'.'.$file->guessExtension();
			$file->move($path,$filename);	
			$app['dao.article']->insert($article);	
			$article->setImage($filename);
			
			

    		//on stocke en session un message de réussite
    		$app['session']->getFlashBag()->add('success', 'Article bien enregistré');

    	}

    	//j'envoie à la vue le formulaire grâce à $articleForm->createView() 
    	return $app['twig']->render('ajout.article.html.twig', array(
    			'articleForm' => $articleForm->createView(),
    			'article' => $article
    	));
	}
	
	public function RegisterAction(Application $app, Request $request){
        $user=new User();
        $articleForm = $app['form.factory']->create(UserRegisterType::class, $user);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){			
            $salt = substr(md5(time()),0,23);
            $user->setSalt($salt);
            $encoder=$app['security.encoder.bcrypt'];
            $pwd=$encoder->encodePassword($user->getPassword(), $user->getSalt());
			$user->setPassword($pwd);
			$user->setRole('ROLE_USER');
            $app['dao.user']->insert($user);
			$app['session']->getFlashBag()->add('success', 'Utilisateur bien enregistré');
        }
        return $app['twig']->render('Register.html.twig', array(
            'articleForm' => $articleForm->createView(),
            'user' => $user
        ));
	}
	
	public function advanceSearchAction(Application $app, Request $request){		
		$articles=[];
		$searchForm = $app['form.factory']->create(SearchType::class);
		$searchForm->handleRequest($request);
		$post =[];
		//if($searchForm->isValid()){	
			$post=$request->query->get('search');
			$articles=$app['dao.article']->advanceSearch($post['text']);
		//}
		return $app['twig']->render('search.html.twig', array(
			'searchForm' => $searchForm->createView(),
			'articles'=>$articles,
			'post'=>$request->query->get('search')
		));
	}

	// public function advanceSearchAction(Application $app, Request $request){
	// 	$post=$request->query->get('search');
	// 	$articles=$app['dao.article']->advanceSearch($post);
	// 	return $app['twig']->render('resultat.html.twig', array(
	// 		'articles'=>$articles
	// 	));
	// }
}