<?php
$connect = "";
if(isset($_SESSION['id'])){
    $connect .= "<p>Bonjour {$_SESSION['prenom']}.</p>";
    $connect .= '<a href="./index.php">Accueil</a>';
    $connect .= '<a href="./ajout-jeu.php">Ajouter un jeu</a>';
    $connect .= '<a href="./deconnexion.php">Se déconnecter</a>';
}else{
    $connect .= '<a href="./index.php">Accueil</a>';
    $connect .= '<a href="./inscription.php">Inscription</a>';
    $connect .= '<a href="./connexion.php">Connexion</a>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eval-Ludothèque</title>
    <link rel="stylesheet" href="./src/css/style.css">
</head>
<body>
    <header>
        <nav>
            <?php echo $connect; ?>
        </nav>
    </header>