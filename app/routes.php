<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond

////////////////////\\\\\\\\\\\\\\\\\\\\\\
//////////////// NAVBAR \\\\\\\\\\\\\\\\\\
////////////////////\\\\\\\\\\\\\\\\\\\\\\

//page d'accueil qui affiche tout les formations
$app->get('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');

//page affichant les categories
$app->get('/category', 'WF3\Controller\HomeController::categoryAction')
->bind('category');

//page qui affiche les 5 dernieres formations
$app->get('/dernieres_formations', 'WF3\Controller\HomeController::lastFiveFormationsAction')->bind('lastFormations');

//affiche les formations suivant la categorie demander
$app->get('/category/Formation/{id}', 'WF3\Controller\HomeController::categoryFormationsAction')
->assert('id', '\d+')
->bind('categoryFormation');

//page qui affiche la formation demander
$app->get('/formation/{id}', 'WF3\Controller\HomeController::formationAction')
->assert('id', '\d+')
->bind('voirFormation');

$app->match('/intervenant/{id}', 'WF3\Controller\HomeController::intervenantAction')
->assert('id', '\d+')
->bind('voirIntervenant');
                    //login page
$app->get('/login', 'WF3\Controller\HomeController::loginAction')->bind('login');
//si aucune ne correspond : erreur 404

                    ////////////////////ADMIN CONTROLE\\\\\\\\\\\\\\\\\\\\\\

// Route pour la page admin
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

                    ///////////////ROUTE ARTICLES///////////////

// Route pour ajouter un article
$app->match('/admin/ajout/formation', 'WF3\Controller\AdminController::ajoutFormationAction')->bind('ajoutFormation');

// Route pour modifier un article
$app->match('/admin/update/formation/{id}', 'WF3\Controller\AdminController::updateFormationAction')
->assert('id', '\d+')
->bind('updateFormation');

//  Route pour supprimer un article
$app->get('/admin/delete/formation/{id}', 'WF3\Controller\AdminController::deleteFormationAction')
->assert('id', '\d+')
->bind('deleteFormation');

                    ///////////////ROUTE INTERVENANT///////////////

// Route pour ajouter un intervenant
$app->match('/admin/ajout/intervenant', 'WF3\Controller\AdminController::ajoutIntervenantAction')->bind('ajoutIntervenant');

// Route pour modifier un intervenant
$app->match('/admin/update/intervenant/{id}', 'WF3\Controller\AdminController::updateIntervenantAction')
->bind('updateIntervenant');

// Route pour supprimer un intervenant
$app->get('/admin/delete/intervenant/{id}', 'WF3\Controller\AdminController::deleteIntervenantAction')->bind('deleteIntervenant');


                        ///////////////ROUTE CATEGORY///////////////

// Route pour ajouter une category
$app->match('/admin/ajoutCategory', 'WF3\Controller\AdminController::ajoutCategoryAction')
->bind('ajoutCategory');

// Route pour modifier une category
$app->match('/admin/update/category/{id}', 'WF3\Controller\AdminController::updateCategoryAction')
->assert('id', '\d+')
->bind('updateCategory');

// Route pour supprimer une category
$app->get('/admin/delete/category/{id}', 'WF3\Controller\AdminController::deleteCategoryAction')
->assert('id', '\d+')
->bind('deleteCategory');

                    ////////////////////\\\\\\\\\\\\\\\\\\\\\\
                    ////////////// RECHERCHE\\\\\\\\\\\\\\\
                    ////////////////////\\\\\\\\\\\\\\\\\\\\\\


//page résultats du moteur de recherche
$app->get('/searche', 'WF3\Controller\HomeController::searchAction')
->bind('recherche');



//$app->match('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');

//$app->get('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');

//page qui affiche un auteur
// $app->match('/admin/ajout/article', 'WF3\Controller\HomeController::ajoutArticleAction')->bind('ajoutArticle');
// $app->match('/Register', 'WF3\Controller\HomeController::RegisterAction')->bind('RegisterUser');

//page contact
// $app->match('/contact/moi', 'WF3\Controller\HomeController::contactAction')->bind('contactezmoi');

