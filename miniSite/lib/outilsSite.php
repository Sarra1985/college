<?php

// $formatter = new IntlDateFormatter( 'fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE );
// echo $formatter->format( time() );

// $date = DateTimeImmutable::createFromFormat( 'U', time() );
// echo $date->format( 'd-m-Y' );

function afficheDate() {

    // récupération du timestamp
    $now = time();

    $jour = date( 'j', $now );

    $semaine = [ 'Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi' ];
    $jourSemaine = date( 'w', $now );

    $mois_arr = [ '', 'Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre' ];
    $mois = date( 'n', $now );

    $annee = date( 'Y', $now );

    return "<div class='date'>" . $semaine[ $jourSemaine ] . " $jour ".$mois_arr[ $mois ]." $annee</div>";

}

function redimensionneImage( $img, $taille, $type ) {

    switch ( $type ) {
        case 'image/jpeg':
            $image_create_func = 'imagecreatefromjpeg';
            $image_save_func = 'imagejpeg';
            break;
        case 'image/png':
            $image_create_func = 'imagecreatefrompng';
            $image_save_func = 'imagepng';
            break;
        default:
            throw new Exception( 'Unknown image type.' );
    }

    list($width, $height) = getimagesize($img);
    
    $tn = imagecreatetruecolor($taille, $taille) ;
    $image = $image_create_func($img) ;
    imagecopyresampled($tn, $image, 0, 0, 0, 0, $taille, $taille, $width, $height) ;
    
    $image_save_func($tn, $img) ;
}

function showInfo($nom, $arr_bdd) {
    if(isset($_POST[$nom])) {
        echo $_POST[$nom];
    } else if(isset($arr_bdd[$nom])) {
        echo $arr_bdd[$nom];
    }
}
?>