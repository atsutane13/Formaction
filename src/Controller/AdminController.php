<?php
namespace WF3\Controller;

use Silex\Application;
use WF3\Domain\Article;
use WF3\Domain\User;
use WF3\Domain\Category;
use WF3\Domain\Intervenant;
use Symfony\Component\HttpFoundation\Request;
use WF3\Form\Type\ArticleType;
use WF3\Form\Type\RegisterType;
use WF3\Form\Type\CategoryType;
use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class AdminController{
    public function indexAction(Application $app){
        $articles = $app['dao.article']->findAll();	
        $intervenants = $app['dao.intervenant']->findAll();        
        return $app['twig']->render('admin/index.admin.html.twig', array('articles' => $articles, 'intervenants'=>$intervenants));
    }    

                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// ARTICLES \\\\\\\\\\\\\\\\\\
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\

    public function AjoutArticleAction(Application $app, Request $request){
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

    public function updateArticleAction(Application $app, Request $request, $id){
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

                    //////////////////////\\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// INTERVENANT \\\\\\\\\\\\\\\\\\
                    //////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\

// Ajout un intervenant
    public function AjoutIntervenantAction(Application $app, Request $request){
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

// Modifie un intervenant
    public function UpdateIntervenantAction(Application $app, Request $request, $id){
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
                $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');    
            }else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('register')['logo'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $intervenant->setLogo($filename);
                $app['dao.intervenant']->update($id, $intervenant);
                $file->move($path,$filename);
                if($Logo===NULL){
                    
                    $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');
                }
                else{
                    unlink( '../'.$app['upload_dir'] . "/". $logo); 
                    $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');
                }
            }
    	}
        return $app['twig']->render('admin/update.user.html.twig', array(
           'articleForm' => $articleForm->createView(),
           'intervenant'=> $app['dao.intervenant']->find($id),
           'logo'=> $logo
        )); 
    }

// Supprime un intervenant
    public function DeleteIntervenantAction(Application $app, Request $request, $id){
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
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// CATEGORY \\\\\\\\\\\\\\\\\\
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\

// Ajoute une category
public function ajoutCategoryAction(Application $app, Request $request){
        $intervenant=new Category();
        $articleForm = $app['form.factory']->create(CategoryType::class, $intervenant);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){

            $path = __DIR__.'/../../'.$app['upload_dir'];
            $file = $request->files->get('category')['image'];
            $filename = md5(uniqid()).'.'.$file->guessExtension();
            $intervenant->setImage($filename);
            $app['dao.category']->insert($intervenant);
            $file->move($path,$filename);
            $app['session']->getFlashBag()->add('success', 'Category bien enregistré');
        }
        return $app['twig']->render('admin/ajoutCategory.admin.html.twig', array(
            'articleForm' => $articleForm->createView()

        ));
    }

// Modifie une category
    public function updateCategoryAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
        $intervenant = $app['dao.category']->find($id);	
        $logo=$intervenant->getImage();
        $intervenant->setImage(NULL);
        
        $articleForm = $app['form.factory']->create(CategoryType::class, $intervenant);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            if($intervenant->getImage()===NULL){
                $intervenant->setImage($logo);
                $app['dao.category']->update($id, $intervenant);
                $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');    
            }else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('category')['image'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $intervenant->setImage($filename);
                $app['dao.category']->update($id, $intervenant);
                $file->move($path,$filename);
                if(file_exists( '../'.$app['upload_dir'] . "/". $logo)){
                    unlink( '../'.$app['upload_dir'] . "/". $logo); 
                    $app['session']->getFlashBag()->add('success', 'Category modifiée');
                }
                else{
                    $app['session']->getFlashBag()->add('success', 'Category modifiée');
                }
                                         
            }
    	}
        return $app['twig']->render('admin/update.category.html.twig', array(
           'articleForm' => $articleForm->createView(),
            'category'=> $app['dao.category']->find($id),
            'image'=> $logo
        )); 
    }

// Supression d'une category
    public function DeleteCategoryAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $user=$app['dao.category']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Category bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('category'));
    }
}