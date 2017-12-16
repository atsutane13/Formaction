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
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

class HomeController{
	
	/////////////////////\\\\\\\\\\\\\\\\\\\\\\\
	//////////////// AFFICHAGE \\\\\\\\\\\\\\\\\\
	/////////////////////\\\\\\\\\\\\\\\\\\\\\\\
	
	//page d'accueil qui affiche tout les articles
	public function homePageAction(Application $app){
		$formations = $app['dao.article']->getLastFormations();	
		if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}
			return $app['twig']->render('index.html.twig', array('formations' => $formations,'token'=>$tok));
		}
		return $app['twig']->render('index.html.twig', array('formations' => $formations));
	}
	
	// permet de cherche une category
	public function categoryAction(Application $app, Request $request){
		$category = $app['dao.category']-> findAll();
		return $app['twig']->render('category.html.twig', array('categorys' => $category));
	}
	
	// cherche des article en fonction de la category
	public function categoryFormationsAction(Application $app, Request $request, $id){
	$category = $app['dao.article']-> findFormationsByCategory($id);
	return $app['twig']->render('category.Formation.html.twig', array('categories' => $category));
	}
	
	//page qui affiche les 5 derniers articles
	public function lastFiveFormationsAction(Application $app){
		$formations = $app['dao.article']->getLastFormations();
		if($app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			$token=$app['security.token_storage']->getToken();	
			if(NULL!==$token){			
				$tok=$token->getUser();
			}
		 return $app['twig']->render('last_formations.html.twig', array('formations' => $formations,'token'=>$tok));
		}
		return $app['twig']->render('last_formations.html.twig', array('formations' => $formations));
	}

	//page d'affichage d'un article
	public function formationAction(Application $app, $id){
		$formation = $app['dao.article']->find($id);	
		return $app['twig']->render('formation.html.twig', array('formation' => $formation));
	}

	


	// fiche d'un utilisateur
	public function intervenantAction(Application $app,Request $request, $id){
		$intervenant = $app['dao.intervenant']->find($id);
	    //on va chercher la liste des articles écrits par l'utilisateur dont l'id est $id
	    //on utilise la méthode getArticlesFromUser() de la classe ArticleDAO
		$formations = $app['dao.article']->getArticlesFromUser($id);	

		return $app['twig']->render('intervenant.html.twig', array('intervenant' => $intervenant, 'formations' => $formations));
	}

	//page contact
	// public function contactAction(Application $app, Request $request){
	// 	$contactForm = $app['form.factory']->create(ContactType::class);
	// 	$contactForm->handleRequest($request);
	// 	if($contactForm->isSubmitted() && $contactForm->isValid()){
	// 		$data=$contactForm->getData();
	// 		$message= \Swift_Message::newInstance()
	// 		->setSubject($data['subject'])
	// 		->setFrom(array('promo5wf3@gmx.fr'))//le mail correspondra a celui du formulaire
	// 		->setTo(array('ggautier.gael@gmail.com'))//le mail utiliser sera celui donner par l'hebergeur ou au notre
	// 		->setBody($app['twig']->render('contact.email.html.twig',array('name'=>$data['name'],'email'=>$data['email'],'message'=>$data['message'])),
	// 		'text/html');
	// 		$app['mailer']->send($message);
	// 	}
	// 	return $app['twig']->render('contact.html.twig', array('contactForm' => $contactForm->createView() ));
	// }
	
	                /////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// FORMULAIRE \\\\\\\\\\\\\\\\\\
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\

    //page contact
	
	// page de connection de l'admin
    public function loginAction(Application $app, Request $request){//formulaire de connexion en dur.
    	//j'appelle la vue qui contient le formulaire de connexion
    	//error va contenir les éventuels messages d'erreur
    	return $app['twig']->render('login.html.twig', array(
			'error' => $app['security.last_error']($request),
    		'last_username' => $app['session']->get('_security.last_username')
    	));
	}
	
    // public function ajoutArticleAction(Application $app, Request $request){
		// 	//on récupère l'utilisateur connecté
		// 	if(!$app['security.authorization_checker']->isGranted('IS_AUTHENTICATED_FULLY')){
			// 		//return $app->redirect($app['url_generator']->generate('home')); //redirection
			// 		throw new AccessDeniedHttpException(); //error 403m accet interdit
			// 	}
			// 	//je crée un objet article vide
			// 	$article = new Article();
			// 	//je crée mon objet formulaire à partir de la classe ArticleType
			// 	$articleForm = $app['form.factory']->create(ArticleType::class, $article);
			// 	$articleForm->handleRequest($request);
			// 	if($articleForm->isSubmitted() && $articleForm->isValid()){
				// 		$app['dao.article']->insert($article);
				// 	//on stocke en session un message de réussite
				// 		$app['session']->getFlashBag()->add('success', 'Article bien enregistré');
				// 	}
				// 	//j'envoie à la vue le formulaire grâce à $articleForm->createView() 
    // 	return $app['twig']->render('ajout.article.html.twig', array(
		// 			'articleForm' => $articleForm->createView(),
		// 			'article' => $article,
    // 	));
	// }
	
	// public function RegisterAction(Application $app, Request $request){
		//     $user=new User();
		//     $articleForm = $app['form.factory']->create(UserRegisterType::class, $user);
		//     $articleForm->handleRequest($request);
		//     if($articleForm->isSubmitted() && $articleForm->isValid()){			
			//         $salt = substr(md5(time()),0,23);
			//         $user->setSalt($salt);
			//         $encoder=$app['security.encoder.bcrypt'];
			//         $pwd=$encoder->encodePassword($user->getPassword(), $user->getSalt());
			// 		$user->setPassword($pwd);
			// 		$user->setRole('ROLE_USER');
			//         $app['dao.user']->insert($user);
			// 		$app['session']->getFlashBag()->add('success', 'Utilisateur bien enregistré');
			//     }
			//     return $app['twig']->render('Register.html.twig', array(
				//         'articleForm' => $articleForm->createView(),
				//         'user' => $user
				//     ));
				// }
				
				
				/////////////////////\\\\\\\\\\\\\\\\\\\\\\\
				//////////////// RECHERCHE \\\\\\\\\\\\\\\\\\
				/////////////////////\\\\\\\\\\\\\\\\\\\\\\\
				public function searchAction(Application $app, Request $request){
					$formations=$app['dao.article']->advanceSearch($request->query->get('search'));
					return $app['twig']->render('results.search.html.twig', array('formations' => $formations));
				}
				
				// permet de chercher une formation dans une barre de recherche
				// public function advanceSearchAction(Application $app, Request $request){		
				// 	$articles=[];
				// 	$searchForm = $app['form.factory']->create(SearchType::class);
				// 	$searchForm->handleRequest($request);
				// 	$post =[];
				// 	//if($searchForm->isValid()){	
				// 		$post=$request->query->get('search');
				// 		$articles=$app['dao.article']->advanceSearch($post['text']);
				// 		//}
				// 		return $app['twig']->render('search.html.twig', array(
				// 			'searchForm' => $searchForm->createView(),
				// 			'articles'=>$articles,
				// 			'post'=>$request->query->get('search')
				// 		));
				// 	}
					

	

	// public function advanceSearchAction(Application $app, Request $request){
	// 	$post=$request->query->get('search');
	// 	$articles=$app['dao.article']->advanceSearch($post);
	// 	return $app['twig']->render('resultat.html.twig', array(
	// 		'articles'=>$articles
	// 	));
	// }
}