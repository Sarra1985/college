<?php

// redirection vers la page de Login si pas logué
if(!isset($_SESSION['login']) || (isset($_SESSION['login']) && $_SESSION['login']== false )) {
    header('Location: index.php?numPage=4');
}
?>

<h3>Formulaire étudiant</h3>

<?php

foreach ( $_POST as $k => $v ) {
    echo 'POST : ' . $k . ' => ' . $v . '<br>';
    //${$k} = $v; // va générer $nom, $prenom...
}

$mysqli = new mysqli("localhost", "root", "", "ecole");

$msgErrorUpload = '';
$msgError = '';

$fichierUpload = '';
$unEtudiant = "";

// inscription
if ( isset( $_POST[ 'rec_btn' ] ) ) {
    $nomPhoto = '';

    if ( !$_POST[ 'nom' ] ) {
        $msgError .= '<br>Entrez votre nom.';
    }
    if ( !$_POST[ 'prenom' ] ) {
        $msgError .= '<br>Entrez votre prénom.';
    }
    if ( !$_POST[ 'datenaiss' ] ) {
        $msgError .= '<br>Entrez votre date de naissance.';
    }
    if ( !$_POST[ 'rue' ] ) {
        $msgError .= '<br>Entrez votre Rue.';
    }
    if ( !$_POST[ 'cp' ] ) {
        $msgError .= '<br>Entrez votre Code Postal.';
    }
    if ( !$_POST[ 'ville' ] ) {
        $msgError .= '<br>Entrez votre Ville.';
    }

    if (!empty($_FILES['laPhoto'])) {

        echo $_FILES['laPhoto']['tmp_name'] . "<br>"; // position TEMPORAIRE sur le disque dur (du serveur)
        echo $_FILES['laPhoto']['name'] . "<br>";
        echo $_FILES['laPhoto']['type'] . "<br>"; // png ou jpeg
        echo $_FILES['laPhoto']['size']. "<br>"; // 1 000 000 en octet
        echo $_FILES['laPhoto']['error']. "<br>";

        if ($_FILES['laPhoto']['error'] == 0) {
            if ($_FILES['laPhoto']['size'] < 1000000) {


                //http://toto.php?166325826564
                // 
                switch ($_FILES['laPhoto']['type']) {
                    case 'image/png':
                    case 'image/jpeg':
                        // on ajoute le temps devant le nom de la photo.
                        $nomPhoto = time().$_FILES['laPhoto']['name'];

                        move_uploaded_file($_FILES['laPhoto']['tmp_name'], "img/photos/".$nomPhoto);

                        // redimensionner l'image. 50 x 50
                        redimensionneImage("img/photos/".$nomPhoto, 50, $_FILES['laPhoto']['type']);
                        // pour afficher l'image...
                        $fichierUpload = "<img src='img/photos/".$nomPhoto."' width='100' alt='' />";                        
                        break;
                    default:
                        $msgErrorUpload .= "Mauvais type de fichier !<br>";
                }
            } else {
                $msgErrorUpload .= "Fichier trop volumineux !<br>";
            }
        } else {
            //$msgErrorUpload .= "Erreur avec l'image !<br>";
        }
    }

    if ( !$msgError ) {

    // echo $_POST['numetuEdit']."<hr>";

        // chaîne de caractère vide.
        if($_POST['numetuEdit'] == "") {
            // -------------------------------- INSERT INTO

            $sqlInsert = "INSERT INTO `etudiant` (`nom`, `prenom`, `datenaiss`, `rue`, `cp`, `ville`, `photo`) ".
            " VALUES ('".$_POST['nom']."', '".$_POST['prenom']."', '".$_POST['datenaiss']."', '".$_POST['rue']."', '".$_POST['cp']."', '".$_POST['ville']."', '".$nomPhoto."');";

            echo $sqlInsert . "<hr />";

            $mysqli->query($sqlInsert);


        } else {
            // -------------------------------- UPDATE

            $sqlUpdate = "UPDATE `etudiant` SET `nom`='".$_POST['nom']."',`prenom`='".$_POST['prenom']."',`datenaiss`='".$_POST['datenaiss']."',`rue`='".$_POST['rue']."',`cp`='".$_POST['cp']."',`ville`='".$_POST['ville']."' WHERE numetu = ".$_POST['numetuEdit'].";";

            echo $sqlUpdate . "<hr />";
            $mysqli->query($sqlUpdate);
        }
    }
}

// Récupérer les infos d'un étudiant si on a $_GET['numetuEdit'] via MySql.
if(!empty($_GET['numetuEdit'])) {
   //echo $_GET['numetuEdit'];

    // SELECT pour cet étudiant ($_GET['numetuEdit'])
    $sqlUnEtu = "SELECT `numetu`, `nom`, `prenom`, `datenaiss`, `rue`, `cp`, `ville`, `photo` FROM `etudiant` WHERE numetu = " .$_GET['numetuEdit'];

    $result = $mysqli->query($sqlUnEtu);
    $unEtudiant = $result->fetch_assoc();

    $numetuEdit = $_GET['numetuEdit'];
    
    // Affichage des infos dans les values des champs.
    // A l'envoie (rec_btn) faire en sorte de pouvoir différencier si on est en INSERT ou en UPDATE.
    // faire la requete SQL UPDATE. 
}


//-------------------------------------------------
// AFFICHAGE COTE CLIENT !
//-------------------------------------------------

if($msgErrorUpload) {
    echo "<span class='rouge'>" . $msgErrorUpload . "</span><br>";
} else {
    echo $fichierUpload . "<br>";
}

if ( $msgError ) {
    echo "<span class='rouge'>" . $msgError . '</span>';
}
// créer un formulaire, permettant de créer un étudiant.
?>

<form method="post" action="index.php?numPage=2" class='inscription' enctype = 'multipart/form-data'>

    <input type="hidden" name="numetuEdit" value="<?php  showInfo('numetu',$unEtudiant); //echo $numetuEdit; ?>" />
    
    <p>
        <label>Nom</label>
        <input type="text" name="nom" value="<?php
            showInfo('nom',$unEtudiant);
        ?>" />
    </p>
    <p>
        <label>Prénom</label>
        <input type="text" name="prenom" value="<?php
            showInfo('prenom',$unEtudiant);
        ?>" />
    </p>
    <p>
        <label>Naissance</label>
        <input type="date" name="datenaiss" value="<?php
            showInfo('datenaiss',$unEtudiant);
        ?>" />
    </p>

    <p>
        <label>Rue</label>
        <input type="text" name="rue" value="<?php
            showInfo('rue',$unEtudiant);
        ?>" />
    </p>
    <p>
        <label>Code Postal</label>
        <input type="text" name="cp" size="10" value="<?php
            showInfo('cp',$unEtudiant);
        ?>" />
    </p>
    <p>
        <label>Ville</label>
        <input type="text" name="ville" value="<?php
            showInfo('ville',$unEtudiant);
        ?>" />
    </p>

    <p>
        <label>Photo</label> <?php 
        if(!empty($unEtudiant['photo'])) {
            echo "<img src='img/photos/".$unEtudiant['photo']."' width='50' alt='' />";           
        }
        ?>
        <input type = 'file' name = 'laPhoto' />
        
    </p>
<hr>
    <p>
        <label></label>
        <!-- Ecrire dans value 'Update' ou 'Créer' selon le mode UPDATE ou INSERT -->
        <input type='submit' name='rec_btn' value='<?php 
            if(isset($_GET['numetuEdit'])) {
                echo "Update";
            } else {
                echo "Créer";
            }?>' />
    </p>
</form>

