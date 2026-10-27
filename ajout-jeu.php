<?php
session_start();
include ('utils/utils.php');
include ('includes/header.php');
if(!isset($_SESSION['id'])){
    header('Location:connexion.php');
    exit();
}
$choix = "";
$message = "";
try{
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque', 'root', 'root',[PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION]);

    $request = $pdo->prepare('SELECT c.id_categorie, c.nom_categorie FROM categorie c;');
    $request->execute();
    $data = $request->fetchAll(PDO::FETCH_ASSOC);
    foreach($data as $cat){
        $choix .= '<option value="';
        $choix .= $cat["id_categorie"];
        $choix .= '">';
        $choix .= $cat["nom_categorie"];
        $choix .= '</option>';
    }
    if(isset($_POST['ajout'])){
        if(!empty($_POST['titre']) && $_POST['min'] != "" && $_POST['max'] != "" && $_POST['duree'] != "" && $_POST['categorie'] != ""){
            if(filter_var($_POST['min'],FILTER_VALIDATE_INT) !== false && filter_var($_POST['max'],FILTER_VALIDATE_INT) !== false && filter_var($_POST['duree'],FILTER_VALIDATE_INT) !== false && filter_var($_POST['categorie'],FILTER_VALIDATE_INT) !== false){
                if($_POST['min']>=1 && $_POST['max'] >= $_POST['min'] && $_POST['duree'] > 0){
                    $request = $pdo->prepare('SELECT j.titre FROM jeu j WHERE j.titre=?;');
                    $request->bindParam(1,$_POST['titre']);
                    $request->execute();
                    $dataTitre = $request->fetch(PDO::FETCH_ASSOC);
                    if($dataTitre){
                        $message = "Ce jeu existe déja dans le catalogue.";
                    }else{
                        $request = $pdo->prepare('SELECT c.id_categorie FROM categorie c WHERE c.id_categorie=?;');
                        $request->bindParam(1,$_POST['categorie']);
                        $request->execute();
                        $dataId = $request->fetch(PDO::FETCH_ASSOC);
                        if($dataId){
                                $titre = trim($_POST['titre']);
                            $jMin = trim($_POST['min']);
                            $jMax = trim($_POST['max']);
                            $jDuree = trim($_POST['duree']);
                            $idCat = $_POST['categorie'];
                            $idMembre = $_SESSION['id'];
                            $request = $pdo->prepare('INSERT INTO jeu(titre, joueurs_min, joueurs_max, duree, id_categorie, id_membre)VALUES(?, ?, ?, ?, ?, ?);');
                            $request->bindParam(1,$titre);
                            $request->bindParam(2,$jMin);
                            $request->bindParam(3,$jMax);
                            $request->bindParam(4,$jDuree);
                            $request->bindParam(5,$idCat);
                            $request->bindParam(6,$idMembre);
                            $request->execute();

                            $message = "Le jeu à bien été ajouté.";
                        }else{
                            $message = "Catégorie inexistante.";
                        }
                    }
                }else{
                    $message = "Veuillez insérer des valeurs réalistes.";
                }
            }else{
                $message = "Veuillez fournir des nombres entiers.";
            }
        }else{
            $message = "Veuillez remplir les champs.";    
        }

    }
}catch(EXCEPTION $error){
    die($error->getMessage());
}
?>
<main>
    <h2>Ajouter un jeu</h2>
    <section>
        <form action="" method="post">
            <label for="titre">Titre: <input type="text" name="titre" id="titre"></label>
            <label for="min">Joueurs minimum: <input type="number" name="min" id="min"></label>
            <label for="max">Joueurs maximum: <input type="number" name="max" id="max"></label><br>
            <label for="duree">Durée d'une partie: <input type="number" name="duree" id="duree"></label>
            <label for="categorie">Choisissez une catégorie: </label>
            <select name="categorie" id="categorie">
                <option value="">Veuillez choisir une option</option>
                <?php echo $choix; ?>
            </select>
            <input type="submit" name="ajout" value="Ajouter">
        </form>
        <?php echo $message; ?>
    </section>
</main>
<?php include ('includes/footer.php'); ?>