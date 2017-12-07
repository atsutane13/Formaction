<?php
namespace WF3\Controller;

use Silex\Application;
use WF3\Domain\Article;
use WF3\Domain\User;
use WF3\Domain\Intervenant;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\RegisterType;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class AdminController{
    public function indexAction(Application $app){
        $articles = $app['dao.article']->findAll();	
        $intervenants = $app['dao.intervenant']->findAll();


        
        return $app['twig']->render('admin/index.admin.html.twig', array('articles' => $articles, 'intervenants'=>$intervenants));
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
        $intervenant=new Intervenant();
        $articleForm = $app['form.factory']->create(RegisterType::class, $intervenant);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
	
			$path = __DIR__.'/../../'.$app['upload_dir'];
			$file = $request->files->get('register')['logo'];
			$filename = md5(uniqid()).'.'.$file->guessExtension();
			$intervenant->setLogo($filename);
            $app['dao.intervenant']->insert($intervenant);
			$file->move($path,$filename);
            $app['session']->getFlashBag()->add('success', 'Intervenant bien enregistré');
        }
        return $app['twig']->render('admin/adduser.admin.html.twig', array(
            'articleForm' => $articleForm->createView()

        ));
    }

    public function UpdateUserAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
        $intervenant = $app['dao.intervenant']->find($id);	
        $logo=$intervenant->getLogo();
        $intervenant->setLogo(NULL);
        
        $articleForm = $app['form.factory']->create(RegisterType::class, $intervenant);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            if($intervenant->getLogo()===NULL){
                $intervenant->setLogo($logo);
                $app['dao.intervenant']->update($id, $intervenant);
                $app['session']->getFlashBag()->add('success', 'Intervenant modifier');    
            }else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('register')['logo'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $intervenant->setLogo($filename);
                $app['dao.intervenant']->update($id, $intervenant);
                $file->move($path,$filename);
                unlink( '../'.$app['upload_dir'] . "/". $logo);               
                $app['session']->getFlashBag()->add('success', 'Intervenant modifier');
            }
    	}
        return $app['twig']->render('admin/update.user.html.twig', array(
           'articleForm' => $articleForm->createView(),
            'intervenant'=> $app['dao.intervenant']->find($id),
            'logo'=> $logo
        )); 
    }

    public function DeleteUserAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $articles=$app['dao.article']->deleteArticleByAuthor($id);
        $user=$app['dao.intervenant']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Article bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('usersList'));
    }
}