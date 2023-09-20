<?php

// Doit être exécuté pour CHAQUE fichier php, sinon, perte des infos de session.
// RIEN ne doit être envoyé au navigateur avant cette ligne.
session_start();

//phpinfo();

include "lib/outilsSite.php";
//              0           1           2
$liens =    ['Accueil', 'Tableau', 'Formulaire', 'Vignette', 'Login'];
$pagesSite = ['accueil.php', 'tableau.php', 'form.php', 'vignette.php', 'login.php'];

// 0, correspond à la page d'accueil.
$numPageACharger = 0;
// 
if(!empty($_GET['numPage'])) {
    $numPageACharger = $_GET['numPage'];
}


// tester si la valeur ($numPageACharger) n'est pas hors champs.
// plus grande ou plus petite que la taille du tableau
// $numPageACharger ne doit pas être < 0
// $numPageACharger ne doit pas être > 2 ::: count($liens) = 3;
if($numPageACharger < 0 || $numPageACharger > count($liens)-1) {
    $numPageACharger = 0;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/css.css" />
    <link rel="stylesheet" href="css/menu.css" />
    <link rel="stylesheet" href="css/tableau.css" />

    <title>Mini Site</title>
</head>

<body>
    <?php

    if(!empty($_SESSION['login'])) {
        echo "Login : " . $_SESSION['login'];
    } else {
        echo "Login : Out";
    }

    echo afficheDate();

/* 
Les variables dans la boucle foreach du menu.
$textLien       $key      $numPageACharger
  Accueil       0         0
  Tableau       1         0
  Formulaire    2         0

  On devrait allumer accueil.

L'utilisateur click sur Tableau.
Lien : index.php?numPage=1

$textLien       $key      $numPageACharger
  Accueil       0         1
  Tableau       1         1
  Formulaire    2         1
  
  On devrait allumer Tableau.
*/

    // menu
    $menu = "<nav class='topMenu'><ul>";

    foreach($liens as $key => $textLien) {

        $menu .= "\n<li><a ";
        if($numPageACharger == $key) {
            $menu .= " class='actif' ";
        }

        if($textLien == "Login"){
            if(!empty($_SESSION['login']) && $_SESSION['login'] ) {
                $menu .= " href='php/logout.php'>LogOut</a></li>";
            } else {
                $menu .= " href='index.php?numPage=".$key."'>Login</a></li>";
            }
            
        } else {
            $menu .= " href='index.php?numPage=".$key."'>". $textLien."</a></li>";  
        }
        
        // if($numPageACharger == $key) {
        //     $menu .= "\n<li><a class='actif' href='index.php?numPage=".$key."'>". $textLien."</a></li>";
        // } else {
        //     $menu .= "\n<li><a href='index.php?numPage=".$key."'>". $textLien."</a></li>";
        // }
    }
    $menu .= "</ul></nav>";
    echo $menu;
    ?>

    <section class='sousPage'><?php
        // charge la page
        require "php/".$pagesSite[$numPageACharger];
    ?></section>

</body>
</html>
