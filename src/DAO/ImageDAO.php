<?php

namespace WF3\DAO;

use WF3\Domain\Image;

class ImageDAO extends DAO {

    public function tailImage(){

        if(!empty($_FILES)){
            //je vérifie qu'il n'y a pas eu d'erreur
            $errors = [];
            //s'il n'y a pas d'erreur, error vaut 0 
            if($_FILES['fichier']['error'] > 0){
                $errors[] = 'erreur lors du transfert';
            }
            
            //vérification de la taille
            $maxsize = 1048576; //taille max: 1 Mo
            if($_FILES['fichier']['size'] > $maxsize){
                $errors[] = 'fichier trop volumineux';
            }
            
            //Vérification de l'extension du fichier
            //pour tester l'extension du fichier
            $fileInfo = pathinfo($_FILES['fichier']['name']);
            //print_r($fileInfo);
            $extension = $fileInfo['extension'];
            //ici on veut une image :soit jpg, soit png, soit gif
            $extensionsAutorisees = array('jpg', 'png', 'gif');
            if(!in_array($extension, $extensionsAutorisees)){
                $errors[] = 'extension de fichier invalide';
            }
                
            if(empty($errors)){
                $resultat = $bdd->query('INSERT INTO image (image_pc) VALUES ("' . $nomFichier . '.' .$extension . '")');
                //pas d'erreurs

                //on veut envoyer nos fichiers dans le dossier uploads
                $folder = 'uploads/';
                move_uploaded_file($_FILES['fichier']['tmp_name'], $folder.$nomFichier . '.'. $extension);
                //on renomme le fichier de afçon à ce que son nom soit unique pour éviter qu'il soit écrasé
                // $nomFichier = md5(uniqid(rand(), true));
                
                // on va crée une miniature
                // je décide que mes miniatures ont une largeur de 200px
                $newWidth = 200;

                if($extension == 'jpg'){
                    // jpeg ou jpg
                    $newImage = imagecreatefromjpeg($_FILES['fichier']['tmp_name']);
                }
                elseif($extension == 'png'){
                    // png
                    $newImage = imagecreatefrompng($_FILES['fichier']['tmp_name']);
                }
                else{
                    // fichier gif
                    $newImage = imagecreatefromgif($_FILES['fichier']['tmp_name']);
                }

                // on recupere les dimansions de l'image
                // largeur
                $imageWidth = imagesx($newImage);
                // hauteur
                $imageheight = imagesx($newImage);

                // j'ai decide de la largeur de mes miniature(200px),
                // je dois donc calculer la nouvelle hauteur (on doit conserver le ratio pour ne pas déformer l'image)
                // on calcule la nouvelle hauteur
                $newHeight = ($imageheight * $newWidth) / $imageWidth;

                // on crée la miniature
                $miniature = imagecreatetruecolor($newWidth, $newHeight);

                // on va ensuite "remplir" la miniature a partir de l'image envoyée
                imagecopyresampled($miniature,$newImage, 0, 0, 0, 0, $newWidth, $newHeight, $imageWidth, $imageheight);

                // on definit le dossier qui va contenir les miniature
                $thumbnailsFolder = '../../web/uploads/thumbnails/';

                // on testl'extension
                if($extension == 'jpg'){
                    imagejpeg($miniature, $thumbnailsFolder . $nomFichier . '.' . $extension);
                }
                elseif($extension == 'png'){
                    imagepng($miniature, $thumbnailsFolder . $nomFichier . '.' . $extension);
                }
                else{
                    imagegif($miniature, $thumbnailsFolder . $nomFichier . '.' . $extension);
                }


                //on demande d'uploader le fichier dans le dossier $folder, avec le nom généré et l'extension originale
                move_uploaded_file($_FILES['fichier']['tmp_name'], $folder.$nomFichier . '.'. $extension);
                
                //on enregistre dans la base
                $resultat = $bdd->query('INSERT INTO image (image_portable) VALUES ("' . $nomFichier . '.' .$extension . '")');
                
                echo 'fichier bien enregistré';
            }
            else{//erreur
                foreach($errors as $error){
                    echo $error.'<br>';
                }
            }
        }
    }
}