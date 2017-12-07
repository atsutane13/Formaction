<?php
// ce fichier contient la liste des routes = url ) que l'on va accepter
//silex va parcourir les routes de haut en bas et s'arrête à la première qui correspond

//page d'accueil qui affiche tout les articles
$app->get('/', 'WF3\Controller\HomeController::homePageAction')->bind('home');
//bind permet de nommer une route
//on peut alors appeler cette route dans une vue twig pour générer l'url correspondante

//page qui affiche les 5 derniers articles
$app->get('/derniers_articles', 'WF3\Controller\HomeController::lastFiveArticlesAction')->bind('lastArticles');

//page qui affiche un article
$app->get('/article/{id}', 'WF3\Controller\HomeController::articleAction')
->assert('id', '\d+')
->bind('voirArticle');

//page qui suuprime un article
$app->get('/article/delete/{id}', 'WF3\Controller\HomeController::deleteArticleAction')
->assert('id', '\d+')
->bind('deleteArticle');

$app->get('/category', 'WF3\Controller\HomeController::categoryAction')
->bind('category');

$app->get('/categoryArt/{id}', 'WF3\Controller\HomeController::categoryArtAction')
->assert('id', '\d+')
->bind('categoryArt');

//page qui affiche un auteur
$app->match('/user/{id}', 'WF3\Controller\HomeController::userAction')
->assert('id', '\d+')
->bind('voirUser');

//page contact
$app->match('/contact/moi', 'WF3\Controller\HomeController::contactAction')->bind('contactezmoi');

//page résultats du moteur de recherche
$app->get('/seurche', 'WF3\Controller\HomeController::seurcheAction')
    ->bind('rechercheParTitre');

//page de création d'article
    //match permet d'accepter les requêtes en get et en post
$app->match('/ajout/article', 'WF3\Controller\HomeController::ajoutArticleAction')->bind('ajoutArticle');

   //login page
$app->get('/login', 'WF3\Controller\HomeController::loginAction')->bind('login');
//si aucune ne correspond : erreur 404

$app->get('/admin', 'WF3\Controller\AdminController::indexAction')->bind('homeAdmin');

$app->match('/admin/update/{id}', 'WF3\Controller\AdminController::updateAction')
->assert('id', '\d+')
->bind('updateAdmin');

$app->match('/ajout/article', 'WF3\Controller\HomeController::AddArticleAction')->bind('ajoutAdArticle');

$app->match('/admin/ajout/user', 'WF3\Controller\AdminController::AddUserAction')->bind('ajoutUser');

$app->match('/Register', 'WF3\Controller\HomeController::RegisterAction')->bind('RegisterUser');

$app->match('/admin/update/user/{id}', 'WF3\Controller\AdminController::UpdateUserAction')
->bind('updateUser');

$app->get('/deleteCategory/{id}', 'WF3\Controller\AdminController::DeleteCategoryAction')
->assert('id', '\d+')
->bind('deleteCategory');

$app->match('/updateCategory/{id}', 'WF3\Controller\AdminController::updateCategoryAction')
->assert('id', '\d+')
->bind('updateCategory');

$app->match('/ajoutCategory', 'WF3\Controller\AdminController::ajoutCategoryAction')
->bind('ajoutCategory');

$app->get('/admin/delete/user/{id}', 'WF3\Controller\AdminController::DeleteUserAction')->bind('deleteUser');

$app->match('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');

//$app->get('/advancesearch','WF3\Controller\HomeController::advanceSearchAction')->bind('advanceSearch');


