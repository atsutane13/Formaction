<?php
namespace WF3\Controller;

use Silex\Application;
use WF3\Domain\Article;
use WF3\Domain\User;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\RegisterType;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class AdminController{
    public function indexAction(Application $app){
        $articles = $app['dao.article']->findAll();	
        $users = $app['dao.user']->findAll();
        
        return $app['twig']->render('admin/index.admin.html.twig', array('articles' => $articles, 'users'=>$users));
    }

    public function updateAction(Application $app, Request $request, $id){
        $article = $app['dao.article']->find($id);	
        $articleForm = $app['form.factory']->create(ArticleType::class, $article);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
    		$app['dao.article']->update($id, $article);
    		$app['session']->getFlashBag()->add('success', 'Article bien enregistré');

    	}
        return $app['twig']->render('admin/update.admin.html.twig', array(
            'articleForm' => $articleForm->createView()
        ));        
    }

    

    public function AddUserAction(Application $app, Request $request){
        $user=new User();
        $articleForm = $app['form.factory']->create(RegisterType::class, $user);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $salt = substr(md5(time()),0,23);
            $user->setSalt($salt);
            $encoder=$app['security.encoder.bcrypt'];
            $pwd=$encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($pwd);
            $app['dao.user']->insert($user);
            $app['session']->getFlashBag()->add('success', 'Utilisateur bien enregistré');
        }
        return $app['twig']->render('admin/adduser.admin.html.twig', array(
            'articleForm' => $articleForm->createView(),
            'user' => $user
        ));
    }

    public function UpdateUserAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
        $user = $app['dao.user']->find($id);	
        $articleForm = $app['form.factory']->create(RegisterType::class, $user);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $salt = substr(md5(time()),0,23);
            $user->setSalt($salt);
            $encoder=$app['security.encoder.bcrypt'];
            $pwd=$encoder->encodePassword($user->getPassword(), $user->getSalt());
            $user->setPassword($pwd);
    		$app['dao.user']->update($id, $user);
    		$app['session']->getFlashBag()->add('success', 'Utilisateur modifier');

    	}
        return $app['twig']->render('admin/update.user.html.twig', array(
            'articleForm' => $articleForm->createView()
        )); 
    }

    public function DeleteUserAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $articles=$app['dao.article']->deleteArticleByAuthor($id);
        $user=$app['dao.user']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('usersList'));
    }
}