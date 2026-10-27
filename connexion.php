<?php
session_start();
include ('utils/utils.php');
$message = "";
if(isset($_POST['connexion'])){
    if(!empty($_POST['email']) && !empty($_POST['mdp1'])){
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)){
            if(mb_strlen($_POST['mdp1'])>= 12){
                try{
                    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque', 'root', 'root',[PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION]);
                    $request = $pdo->prepare('SELECT m.id_membre, m.prenom, m.nom, m.email, m.mot_de_passe FROM membre m WHERE m.email=?;');
                    $request->bindParam(1,$_POST['email'],PDO::PARAM_STR);
                    $request->execute();
                    $data = $request->fetch(PDO::FETCH_ASSOC);
                    if($data){
                        if(password_verify($_POST['mdp1'],$data['mot_de_passe'])){
                            $_SESSION['id'] = $data['id_membre'];
                            $_SESSION['prenom'] = $data['prenom'];
                            $_SESSION['nom'] = $data['nom'];
                            $_SESSION['email'] = $data['email'];
                            header('Location:index.php');
                        }else{
                            $message = "E-mail et/ou mot de passe incorrect.";
                        }
                    }else{
                        $message = "E-mail et/ou mot de passe incorrect.";
                    }
                }catch(EXCEPTION $error){
                    die($error->getMessage());
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

include('includes/header.php');
?>
<main>
    <h2>Connexion</h2>
    <section>
        <form action="" method="post">
            <label for="email">E-mail : <input type="email" name="email" id="email" placeholder="email@mail.com"></label>
            <label for="mdp1">Mot de passe : <input type="password" name="mdp1" id="mdp1" placeholder="12 caractères minimum"></label>
            <input type="submit" name="connexion" value="Valider">
        </form>
    </section>
    <?php echo $message; ?>
</main>
<?php include('includes/footer.php'); ?>