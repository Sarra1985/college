<h3>Tableau Pdo</h3>

<?php

$dsn = 'mysql:dbname=ecole;host=localhost';
$user = 'root';
$password = '';

$pdo = new PDO($dsn, $user, $password);

// créer une requete SQL.
$sql = "SELECT `numetu`, `nom`, `prenom`, `datenaiss`, `rue`, `cp`, `ville`, `photo` FROM `etudiant` ";

// utile pour le premier passage.
$tri = "DESC";

// si on récupère le tri c'est que l'utilisateur a cliqué le lien.
if(!empty($_GET['tri'])) {
    $tri  = $_GET['tri'];

    // requete SQL
    $sql .= " ORDER BY ".$_GET['search']." " . $tri;
    // if($tri == "DESC") {
    //     $tri = "ASC";
    // } else {
    //     $tri = "DESC";
    // }
    $tri = ($tri == "DESC") ? "ASC" : "DESC";
}

echo $sql . "<hr>";

$result = $pdo->prepare($sql);
$result->execute();

$html= "\n<table class='etudiant'>";
$html .= "\n<tr>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=numetu'>numetu</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=nom'>nom</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=prenom'>prenom</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=datenaiss'>datenaiss</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=rue'>rue</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=cp'>cp</a></th>
            <th><a href='index.php?numPage=1&tri=".$tri."&search=ville'>ville</a></th>
            <th>photo</th>
          </tr>";

// Récupération de TOUTES les réponses.
$rowset = $result->fetchAll(PDO::FETCH_ASSOC);
   
if ($rowset) {
    foreach ($rowset as $row) {
        $html .= "\n<tr>";   
        $html .= "\n\t<td>" . $row["numetu"]. "</td>";
        $html .= "\n\t<td>" . $row["nom"]. "</td>";
        $html .= "\n\t<td>" . $row["prenom"]. "</td>";
        $html .= "\n\t<td>" . $row["datenaiss"]. "</td>";
        $html .= "\n\t<td>" . $row["rue"]. "</td>";
        $html .= "\n\t<td>" . $row["cp"]. "</td>";
        $html .= "\n\t<td>" . $row["ville"]. "</td>";
        $html .= "\n\t<td><img src='img/photos/".$row['photo']."' alt=''/></td>";
        $html .= "\n</tr>";
    }   
}

$html .= "</table>";

echo $html;

?>