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
$app->get('/dernieres_formations', 'WF3\Controller\HomeController::lastFiveFormationsAction')->bind('lastArticles');

//page qui affiche la formation demander
$app->get('/article/{id}', 'WF3\Controller\HomeController::articleAction')
->assert('id', '\d+')
->bind('voirArticle');

//login page
$app->get('/login', 'WF3\Controller\HomeController::loginAction')->bind('login');
//si aucune ne correspond : erreur 404

                    ////////////////////ADMIN CONTROLE\\\\\\\\\\\\\\\\\\\\\\



// Route pour la page admin
$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

///////////////ROUTE ARTICLES///////////////

// Route pour modifier un article
$app->match('/admin/update/{id}', 'WF3\Controller\AdminController::updateArticleAction')
->assert('id', '\d+')
->bind('updateAdmin');

// Route pour ajouter un article
$app->match('/admin/ajout/article', 'WF3\Controller\AdminController::AjoutArticleAction')->bind('ajoutAdArticle');

//  Route pour supprimer un article
$app->get('/admin/article/delete/{id}', 'WF3\Controller\AdminController::deleteArticleAction')
->assert('id', '\d+')
->bind('deleteArticle');

///////////////ROUTE INTERVENANT///////////////

// Route pour ajouter un intervenant
$app->match('/admin/ajout/intervenant', 'WF3\Controller\AdminController::AjoutIntervenantAction')->bind('ajoutUser');

// Route pour modifier un intervenant
$app->match('/admin/update/user/{id}', 'WF3\Controller\AdminController::UpdateIntervenantAction')
->bind('updateUser');

// Route pour supprimer un intervenant
$app->get('/admin/delete/intervenant/{id}', 'WF3\Controller\AdminController::DeleteIntervenantAction')->bind('deleteUser');


///////////////ROUTE CATEGORY///////////////

// Route pour supprimer une category
$app->get('/admin/deleteCategory/{id}', 'WF3\Controller\AdminController::DeleteCategoryAction')
->assert('id', '\d+')
->bind('deleteCategory');

// Route pour modifier une category
$app->match('/admin/updateCategory/{id}', 'WF3\Controller\AdminController::updateCategoryAction')
->assert('id', '\d+')
->bind('updateCategory');

// Route pour ajouter une category
$app->match('/admin/ajoutCategory', 'WF3\Controller\AdminController::ajoutCategoryAction')
->bind('ajoutCategory');



                    ////////////////////\\\\\\\\\\\\\\\\\\\\\\
                    ////////////// RECHERCHE\\\\\\\\\\\\\\\
                    ////////////////////\\\\\\\\\\\\\\\\\\\\\\

//affiche les formations suivant la categorie demander
$app->get('/categoryArt/{id}', 'WF3\Controller\HomeController::categoryArtAction')
->assert('id', '\d+')
->bind('categoryArt');

//page résultats du moteur de recherche
$app->get('/seurche', 'WF3\Controller\HomeController::seurcheAction')
->bind('rechercheParTitre');



$app->match('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');

//$app->get('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');

//page qui affiche un auteur
$app->match('/user/{id}', 'WF3\Controller\HomeController::userAction')
->assert('id', '\d+')
->bind('voirUser');
// $app->match('/admin/ajout/article', 'WF3\Controller\HomeController::ajoutArticleAction')->bind('ajoutArticle');
// $app->match('/Register', 'WF3\Controller\HomeController::RegisterAction')->bind('RegisterUser');

//page contact
// $app->match('/contact/moi', 'WF3\Controller\HomeController::contactAction')->bind('contactezmoi');

