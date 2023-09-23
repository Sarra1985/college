
<?php
$liens =    ['Accueil', 'Tableau', 'Formulaire'];
$pagesSite = ['accueil.php', 'tableau.php', 'form.php'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="css/css.css" />
    <link rel="stylesheet" href="css/menu.css" />

    <title>Mini Site</title>
</head>

<body>
    <?php
// menu
$menu = "<nav class='topMenu'><ul>";
foreach($liens as $key => $textLien) {
    $menu .= "\n<li><a href='index.php?nomPage=".$pagesSite[$key]."'>". $textLien."</a></li>";
}
// foreach($pagesSite as $key => $page) {
//     $menu .= "<li><a href='index.php?nomPage=".$page."'>".$liens[$key]."</a></li>";
// }
$menu .= "</ul></nav>";
echo $menu;
    ?>

    <section class='sousPage'>
    <?php
    if(!empty($_GET['nomPage'])) {
        // charge la page
        require "php/".$_GET['nomPage'];        
    }
    ?>
    </section>

</body>
</html>
