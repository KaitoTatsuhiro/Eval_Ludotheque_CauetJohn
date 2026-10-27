<?php
session_start();
include ('utils/utils.php');
include ('includes/header.php');
$message = "";
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION]);
    $request = $pdo->prepare('SELECT j.titre, c.nom_categorie, j.joueurs_min, j.joueurs_max, j.duree, m.prenom FROM jeu j INNER JOIN membre m ON m.id_membre = j.id_membre INNER JOIN categorie c ON c.id_categorie = j.id_categorie ORDER BY j.titre;');
    $request->execute();
    $data = $request->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $tableau){
        $message.= "<ul>";
        $message.= "<li>{$tableau['titre']}: {$tableau['nom_categorie']}</li>";
        $format = formatJoueurs($tableau['joueurs_min'], $tableau['joueurs_max']);
        $message.= "<li>{$format}</li>";
        $duree = categorieDuree($tableau['duree']);
        $message.= "<li>{$duree}</li>";
        $message.= "<li>{$tableau['prenom']}</li>";
        $message.= "</ul>";
    }
}catch(EXCEPTION $error){
    die($error->getMessage());
}

?>
<main>
    <h2>Tableaux des jeux</h2>
    <?php echo $message; ?>
</main>
<?php include ('includes/footer.php'); ?>