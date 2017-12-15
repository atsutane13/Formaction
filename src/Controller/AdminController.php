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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

use Symfony\Component\Security\Core\Encoder\BCryptPasswordEncoder;

class AdminController{
    public function indexAction(Application $app){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $formations = $app['dao.article']->findAll();	
        $intervenants = $app['dao.intervenant']->findAll();        
        return $app['twig']->render('admin/index.admin.html.twig', array('formations' => $formations, 'intervenants'=>$intervenants));
    }    

                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// ARTICLES \\\\\\\\\\\\\\\\\\
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\

    public function ajoutFormationAction(Application $app, Request $request){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $formation = new Article();
        $articleForm = $app['form.factory']->create(ArticleType::class, $formation);
        $selectCats=$app['dao.category']->getCategoryWithId();
        $dropCategory=[];
        foreach($selectCats as $selectCat){
            $dropCategory[$selectCat['category']]=$selectCat['id'];
        }
        $articleForm->add('categoryId', ChoiceType::class,array('choices'=>$dropCategory));

        $selectInters=$app['dao.intervenant']->getIntervenantWithId();
        $dropIntervenant=[];
        foreach($selectInters as $selectInter){
            $dropIntervenant[$selectInter['nom']]=$selectInter['id'];
        }
        $articleForm->add('intervenantId', ChoiceType::class,array('choices'=>$dropIntervenant));
        
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $app['dao.article']->insert($formation);
            $app['session']->getFlashBag()->add('success', 'Formation bien enregistré');
        }
        return $app['twig']->render('admin/ajout.formation.html.twig', array(
            'articleForm' => $articleForm->createView(),
            'formation' => $formation
        ));
    }

    public function updateFormationAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $formation = $app['dao.article']->find($id);	
        $formation->setIntervenantId($app['dao.article']->getIntervenantId($id));
        $articleForm = $app['form.factory']->create(ArticleType::class, $formation);
        $selectCats=$app['dao.category']->getCategoryWithId();

        $dropCategory=[];
        foreach($selectCats as $selectCat){
            $dropCategory[$selectCat['category']]=$selectCat['id'];
        }
        $articleForm->add('categoryId', ChoiceType::class,array('choices'=>$dropCategory));

        $selectInters=$app['dao.intervenant']->getIntervenantWithId();
        $dropIntervenant=[];
        foreach($selectInters as $selectInter){
            $dropIntervenant[$selectInter['nom']]=$selectInter['id'];
        }
        $articleForm->add('intervenantId', ChoiceType::class,array('choices'=>$dropIntervenant));
        
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            $app['dao.article']->update($id, $formation);
            $app['session']->getFlashBag()->add('success', 'Formation bien enregistré');
        }
        return $app['twig']->render('admin/update.formation.html.twig', array(
            'articleForm' => $articleForm->createView()
        ));        
    }

    //page de suppression d'article
    public function deleteFormationAction(Application $app, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $article = $app['dao.article']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Formation bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }

                    //////////////////////\\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// INTERVENANT \\\\\\\\\\\\\\\\\\
                    //////////////////////\\\\\\\\\\\\\\\\\\\\\\\\\

// Ajout un intervenant
    public function ajoutIntervenantAction(Application $app, Request $request){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $intervenant=new Intervenant();
        $articleForm = $app['form.factory']->create(RegisterType::class, $intervenant);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){	
            if($intervenant->getLogo()===NULL){
                $app['dao.intervenant']->insert($intervenant);
                $app['session']->getFlashBag()->add('success', 'Intervenant bien enregistré');
            }else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('register')['logo'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $intervenant->setLogo($filename);
                $app['dao.intervenant']->insert($intervenant);
                $file->move($path,$filename);
                $app['session']->getFlashBag()->add('success', 'Intervenant bien enregistré');
            }
        }
        return $app['twig']->render('admin/ajout.intervenant.html.twig', array(
            'articleForm' => $articleForm->createView()
        ));
    }

// Modifie un intervenant
    public function updateIntervenantAction(Application $app, Request $request, $id){
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
                if($Logo==NULL){
                    
                    $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');
                }
                else{
                    unlink( '../'.$app['upload_dir'] . "/". $logo); 
                    $app['session']->getFlashBag()->add('success', 'Intervenant modifiée');
                }
            }
    	}
        return $app['twig']->render('admin/update.intervenant.html.twig', array(
           'articleForm' => $articleForm->createView(),
           'intervenant'=> $app['dao.intervenant']->find($id),
           'logo'=> $logo
        )); 
    }

// Supprime un intervenant
    public function deleteIntervenantAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
            //return $app->redirect($app['url_generator']->generate('home')); //redirection
            throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $articles=$app['dao.article']->deleteFormationByIntervenant($id);
        $user=$app['dao.intervenant']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Intervenant bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('homeAdmin'));
    }
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\
                    //////////////// CATEGORY \\\\\\\\\\\\\\\\\\
                    /////////////////////\\\\\\\\\\\\\\\\\\\\\\\

// Ajoute une category
public function ajoutCategoryAction(Application $app, Request $request){
    if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
        //return $app->redirect($app['url_generator']->generate('home')); //redirection
        throw new AccessDeniedHttpException(); //error 403m accet interdit
    }
        $category=new Category();
        $articleForm = $app['form.factory']->create(CategoryType::class, $category);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            if($category->getImage()===NULL){
                $app['dao.category']->insert($category);
                $app['session']->getFlashBag()->add('success', 'Categorie bien enregistré');
            } else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('category')['image'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $category->setImage($filename);
                $app['dao.category']->insert($category);
                $file->move($path,$filename);
                $app['session']->getFlashBag()->add('success', 'Categorie bien enregistré');
            }
        }
        return $app['twig']->render('admin/ajout.category.html.twig', array(
            'articleForm' => $articleForm->createView()

        ));
    }

// Modifie une category
    public function updateCategoryAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
		}
        $category = $app['dao.category']->find($id);	
        $logo=$category->getImage();
        $category->setImage(NULL);
        
        $articleForm = $app['form.factory']->create(CategoryType::class, $category);
        $articleForm->handleRequest($request);
        if($articleForm->isSubmitted() && $articleForm->isValid()){
            if($category->getImage()===NULL){
                $category->setImage($logo);
                $app['dao.category']->update($id, $category);
                $app['session']->getFlashBag()->add('success', 'categorie modifiée');    
            }else{
                $path = __DIR__.'/../../'.$app['upload_dir'];
                $file = $request->files->get('category')['image'];
                $filename = md5(uniqid()).'.'.$file->guessExtension();
                $category->setImage($filename);
                $app['dao.category']->update($id, $category);
                $file->move($path,$filename);
                if($logo==NULL){
                    $app['session']->getFlashBag()->add('success', 'Categorie modifiée');
                }
                else{
                    unlink( '../'.$app['upload_dir'] . "/". $logo); 
                    $app['session']->getFlashBag()->add('success', 'Categorie modifiée');
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
    public function deleteCategoryAction(Application $app, Request $request, $id){
        if(!$app['security.authorization_checker']->isGranted('ROLE_ADMIN')){
			//return $app->redirect($app['url_generator']->generate('home')); //redirection
			throw new AccessDeniedHttpException(); //error 403m accet interdit
        }
        $user=$app['dao.category']->delete($id);
        //on crée un message de réussite dans la session
        $app['session']->getFlashBag()->add('success', 'Categorie bien supprimé');
        //on redirige vers la page d'accueil
        return $app->redirect($app['url_generator']->generate('category'));
    }
}