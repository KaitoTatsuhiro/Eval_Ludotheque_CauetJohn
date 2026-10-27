<?php
session_start();
include ('utils/utils.php');
include ('includes/header.php');
if(!isset($_SESSION['id'])){
    header('Location:connexion.php');
    exit();
}
$info = "";
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque', 'root', 'root',[PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION]);
    $request = $pdo->prepare('SELECT m.prenom, m.nom, m.email, m.date_inscription, j.titre FROM membre m INNER JOIN jeu j ON j.id_membre = m.id_membre WHERE m.id_membre=?;');
    $request->bindParam(1,$_SESSION['id']);
    $request->execute();
    $data = $request->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $val){
        $info .= "<p>Prenom: {$val['prenom']}</p>";
        $info .= "<p>Nom: {$val['nom']}</p>";
        $info .= "<p>E-mail: {$val['email']}</p>";
        $info .= "<p>Date d'inscription: {$val['date_inscription']}</p>";
    }
    $info .= "<h3>Liste de ses jeux</h3><ul>";
    foreach($data as $val){
        
        $info .= "<li>{$val['titre']}</li>";
    }
        $info .= "</ul>";
}catch(EXCEPTION $error){
    die($error->getMessage());
}
?>
<main>
    <h2>Mon compte</h2>
    <?php echo $info; ?>
</main>
<?php
include('includes/footer.php');
?>