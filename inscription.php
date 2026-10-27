<?php
session_start();
include ('utils/utils.php');
$message = "";
$form = "";
if(isset($_POST['inscription'])){
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['mdp1']) && !empty($_POST['mdp2'])){
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            if(mb_strlen($_POST['mdp1'])>= 12 && mb_strlen($_POST['mdp2'])>= 12){
                if($_POST['mdp1'] == $_POST['mdp2']){
                    try{
                        $pdo = new PDO('mysql:host=localhost;dbname=ludotheque', 'root', 'root',[PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION]);
                        $request = $pdo->prepare('SELECT m.email FROM membre m WHERE m.email=?;');
                        $request->bindParam(1,$_POST['email'],PDO::PARAM_STR);
                        $request->execute();
                        $data = $request->fetch(PDO::FETCH_ASSOC);
                        if($data){
                            $message = "E-mail déjà utilisé.";
                        }else{
                            $prenom = trim($_POST['prenom']);
                            $nom = trim($_POST['nom']);
                            $email = trim($_POST['email']);

                            $mdp = password_hash($_POST['mdp1'],PASSWORD_DEFAULT);

                            $request = $pdo->prepare('INSERT INTO membre (prenom, nom, email, mot_de_passe) VALUES(?, ?, ?, ?);');
                            $request->bindParam(1,$prenom,PDO::PARAM_STR);
                            $request->bindParam(2,$nom,PDO::PARAM_STR);
                            $request->bindParam(3,$email,PDO::PARAM_STR);
                            $request->bindParam(4,$mdp,PDO::PARAM_STR);
                            $request->execute();
                            $message = "Votre compte à bien été enregistré.";
                        }
                    }catch(EXCEPTION $error){
                        die($error->getMessage());
                    }
                }else{
                    $message = "Veuillez renseigner un mot de passe identique";
                }
            }else{
                $message = "Mot de passe trop court.";
            }
        }else{
            $message = "Mauvais format de l'e-mail.";
        }
    }else{
        $message = "Remplissez tous les champs.";
    }
}
//Garde les info si erreur
$form .= '<label for="prenom">Prénom : <input type="text" name="prenom" value="';
if(isset($_POST['prenom'])){
$form .= $_POST['prenom'];
}
$form .= '" id="prenom" placeholder="Jean"></label>';
$form .= '<label for="nom">Nom : <input type="text" name="nom" id="nom" value="';
if(isset($_POST['nom'])){
$form .= $_POST['nom'];
}
$form .= '" placeholder="Dupont"></label><br>';
$form .= '<label for="email">E-mail : <input type="email" name="email" id="email" value="';
if(isset($_POST['email'])){
$form .= $_POST['email'];
}
$form .= '" placeholder="email@mail.com"></label><br>';

include('includes/header.php');
?>
<main>
    <h2>Inscription</h2>
    <section>
        <form action="" method="post">
            <?php echo $form; ?>
            <label for="mdp1">Mot de passe : <input type="password" name="mdp1" id="mdp1" placeholder="12 caractères minimum"></label>
            <label for="mdp2">Confirmation du mot de passe : <input type="password" name="mdp2" id="mdp2" placeholder="12 caractères minimum"></label>
            <input type="submit" name="inscription" value="Valider">
        </form>
    </section>
    <?php echo $message; ?>
</main>
<?php include('includes/footer.php'); ?>