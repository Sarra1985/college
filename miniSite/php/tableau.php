<h3>Tableau</h3>

<?php
//mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function getIp(){
    if(!empty($_SERVER['HTTP_CLIENT_IP'])){
      $ip = $_SERVER['HTTP_CLIENT_IP'];
    }elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
      $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }else{
      $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
echo '<p>L adresse IP de l utilisateur est  ('.getIp() . ')</p>';

// se connecter à Mysql ET sélectionner une BDD.
$mysqli = new mysqli("localhost", "root", "", "ecole");

// Delete
if(!empty($_GET['numetuDel'])) {
    // supprimer la photo
    $sqlPhoto = "SELECT photo FROM etudiant WHERE numetu = ".$_GET['numetuDel'];
    $reponse = $mysqli->query($sqlPhoto);
    $row = $reponse->fetch_assoc();
    if($row['photo']){
        unlink("img/photos/".$row['photo']);
    }
    // suppression de l'étudiant
    $sqlDel = "DELETE FROM `etudiant` WHERE `numetu` =".$_GET['numetuDel'];
    $mysqli->query($sqlDel);
}

//----------------------------------
/*               $tri           $_GET['tri']            $tri dans le lien  
1er Passage      DESC           /                       DESC
1er click        DESC           DESC                    ASC
2nd click        DESC           ASC                     DESC
*/
$nom_table = "etudiant"; //"notation"; //

// créer une requete SQL. 
// Selection de tous les étudiants
// $sql = "SELECT `numetu`, `nom`, `prenom`, `datenaiss`, `rue`, `cp`, `ville`, `photo` FROM ".$nom_table;

$sql = "SELECT * FROM ".$nom_table;

// utile pour le premier passage.
$tri = "DESC";
$oldSearch = '';

// si on récupère le tri c'est que l'utilisateur a cliqué le lien.
if(!empty($_GET['tri'])) {
    $oldSearch = $_GET['search'];
    $tri  = $_GET['tri'];

    // requete SQL
    $sql .= " ORDER BY ".$_GET['search']." " . $tri;

    if( !$_GET['oldSearch'] || $_GET['search'] == $_GET['oldSearch']){
        // if($tri == "DESC") {
        //     $tri = "ASC";
        // } else {
        //     $tri = "DESC";
        // }
        $tri = ($tri == "DESC") ? "ASC" : "DESC";
    } else {
        $tri = "DESC";
    }
}

echo $sql . "<hr>";

// envoie de la requete et récupération de la réponse.
// la flèche -> en PHP, c'est le point . en JS.
$result = $mysqli->query($sql);


//-----------------------------------------------------------------------------
// 1) Récupérer dans un tableau, la liste des noms des champs (quelque part dans $result). 
$champs = [];
// $result->fetch_field()
//$champs = ['numetu', 'nom', 'prenom'];

$row = $result->fetch_assoc();
// echo $row['nom'];
foreach($row as $key => $value){
    $champs[] = $key;
}
// var_dump($champs);
// on remet le curseur à 0.
$result->data_seek(0);

//-----------------------------------------------------------
$html= "\n<table class='etudiant'>";

$flecheDefault = "&#9660;";

if($tri == "DESC"){
    $fleche = "&#9660;";
} else {
    $fleche = "&#9650;";
}


// Récupération du nom des champs
// Autre méthdode.
// $resultChamps = $mysqli->query("SHOW COLUMNS FROM ".$nom_table);
// while($row = $resultChamps->fetch_assoc()) {
//     $champs[] = $row['Field'];
// }


// ▼ : &#9660;
$html .= "\n<tr>";

foreach ($champs as $ch) {

    if(isset($_GET['search']) && $ch != $_GET['search']){
        $f = $flecheDefault;
    } else {
        $f = $fleche;
    }

    $html .= "<th><a href='index.php?numPage=1&tri=".$tri."&search=".$ch."&oldSearch=".$oldSearch."'>".$f." ".$ch."</a></th>";
}

// <th><a href='index.php?numPage=1&tri=".$tri."&search=nom'>".$fleche." nom</a></th>
// <th><a href='index.php?numPage=1&tri=".$tri."&search=prenom'>".$fleche."prenom</a></th>
// <th><a href='index.php?numPage=1&tri=".$tri."&search=datenaiss'>".$fleche."datenaiss</a></th>
// <th><a href='index.php?numPage=1&tri=".$tri."&search=rue'>".$fleche."rue</a></th>
// <th><a href='index.php?numPage=1&tri=".$tri."&search=cp'>".$fleche."cp</a></th>
// <th><a href='index.php?numPage=1&tri=".$tri."&search=ville'>".$fleche."ville</a></th>

// <th>Photo</th>
if($nom_table == "etudiant" ){
    if(!empty($_SESSION['login']) && $_SESSION['login'] ) {
        $html .= "<th>Edit</th>  <th>Effacer</th>"; 
    }
}
$html .= "</tr>";


// parcourt de la réponse.
//while($row = $result->fetch_array(MYSQLI_ASSOC)) {
while($row = $result->fetch_assoc()) {
    $html .= "\n<tr>";   

    foreach ($champs as $ch) {
        if($ch == 'photo'){
            $html .= "\n\t<td><img src='img/photos/".$row[$ch]."' alt=''/></td>";
            continue;
        }
    
        $html .= "\n\t<td>" . $row[$ch]. "</td>";
    }
    // données de la table etudiant
    // $html .= "\n\t<td>" . $row["numetu"]. "</td>";
    // $html .= "\n\t<td>" . $row["nom"]. "</td>";
    // $html .= "\n\t<td>" . $row["prenom"]. "</td>";
    // $html .= "\n\t<td>" . $row["datenaiss"]. "</td>";
    // $html .= "\n\t<td>" . $row["rue"]. "</td>";
    // $html .= "\n\t<td>" . $row["cp"]. "</td>";
    // $html .= "\n\t<td>" . $row["ville"]. "</td>";
    // lien pour l'édition

    if($nom_table == "etudiant" ){

        // SI logué
        if(!empty($_SESSION['login']) && $_SESSION['login'] ) {
            $html .= "\n\t<td><a href='index.php?numPage=2&numetuEdit=" . $row["numetu"]. "'>🖉</a></td>";
            // lien pour l'édition
            // $html .= "\n\t<td><a onclick='deleteEtudiant(" . $row["numetu"]. ", `" . $row["nom"]. "`);' href='#'>DEL</a></td>";
            // $html .= "\n\t<td><a onclick='deleteEtudiant(" . $row["numetu"]. ", \"" . $row["nom"]. "\");' href='#'>DEL</a></td>";
            $html .= "\n\t<td><input type='button' value='🗑' onclick='deleteEtudiant(" . $row["numetu"]. ", \"" . $row["nom"]. "\");' ></td>";
        }
    }

    $html .= "\n</tr>";
}

$html .= "</table>";
echo $html;
?>
<script>
    function deleteEtudiant(id, nom){
        if(window.confirm("Effacer étudiant : " + nom)) {
            // url del
            window.location = "index.php?numPage=1&numetuDel="+id;
        }
    }
</script>





