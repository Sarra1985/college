<h3>Vignette / ${var} dynamique</h3>

<?php

// une fois connecté à la BDD, récupérer le nom et la photo des étudiants.

// connexion à Mysql.
$mysqli = new mysqli("localhost", "root", "", "ecole");

// requete SQL... $result.
$sql = "SELECT numetu, nom, photo FROM etudiant";
$result = $mysqli->query($sql);

// while ($result) {

while($row = $result->fetch_assoc()) {
    // echo $row['numetu'] . " " . $row['nom'] . " " . $row['photo'] . "<br>";
    //echo "<img src='img/photos/".$row['photo']."' alt=''/>";
        // générer une image contenant la photo et le nom écrit dessous.
    creeVignette('img/photos/', $row['photo'], $row['nom'], 80, 90);
}


function creeVignette($cheminPhoto, $nomPhoto, $nomEtudiant, $largeur, $hauteur) {
    // Première image, crée avec php
    $image = imagecreatetruecolor($largeur, $hauteur);

    // Couleur de fond
    $trans_colour = imagecolorallocatealpha($image, 142, 11, 83, 127);
    imagefill($image, 0, 0, $trans_colour);

    // couleur d'écriture
    $text_color = imagecolorallocate($image, 255, 255, 255);

    // Phrases dans l'image
    imagestring($image, 2, 10,  $hauteur-20, $nomEtudiant, $text_color); 

    // Images mises dans une variable
    $imageSansPhp = $cheminPhoto.$nomPhoto;

    //Vérifie quel format est $imageSansPhp, Gif, Jpg ou Png
    $condition = GetImageSize($imageSansPhp);

    if ($condition[2] == 1) //gif
      $im2 = imagecreatefromgif("$imageSansPhp");
    if ($condition[2] == 2) //jpg
    $im2 = imagecreatefromjpeg($imageSansPhp);
    if ($condition[2] == 3) //png
      $im2 = imagecreatefrompng("$imageSansPhp");

    //Met les 2 images ensemble, /2 pour centrer une image sur l'autre
    imagecopy($image, $im2, (imagesx($image) / 2) - (imagesx($im2) / 2), (imagesy($image) / 2) - (imagesy($im2) / 2)-10, 0, 0, imagesx($im2), imagesy($im2));

    // Création de l'image finale
    // !!! jpg, gif
    imagepng($image, $cheminPhoto."_0_".$nomPhoto, 0);

    echo "<img src='". $cheminPhoto."_0_".$nomPhoto."' alt=''/>&nbsp;";

    //Détruit les variables pour la mémoire
    imagedestroy($image);
    imagedestroy($im2);

}


//---------------------------------------------------------------------------
echo "<h4>Création de variable dynamiquement.</h4>";
$a = "coucou";

// je crée la variable $coucou.
${$a} = 2;
${"truc"} = 12;
echo $coucou; // affiche 8.
echo "<br>" . $truc;


if($coucou > 5) {
    $laFx = "a_fx";
} else  {
    $laFx = "b_fx";
} 
// on exécute la fonction a_fx() ou b_fx()
$laFx();

function a_fx() {
    echo "<p>A fx</p>";
}
function b_fx() {
    echo "<p>B fx</p>";
}

?>