<?php
session_start();

//BDD
require('src/bdd.php');

// Vérifier si tous les champs sont remplis

if (!empty($_POST['pseudo']) && !empty ($_POST['email']) && !empty($_POST['password']) && !empty($_POST['password_confirm'])) {
 
 $pseudo = $_POST['pseudo'];
 $email = $_POST['email'];
 $password = $_POST['password'];
 $password_confirm = $_POST['password_confirm'];

 // Vérifier si le mot de passe et la confirmation sont identiques

 if ($password != $password_confirm) {
  header('location: ./?error=1&pass=1');
  exit;
 }

 // Si l'email a déjà été utilisé et est dans la base de données

 $req = $db->prepare("SELECT count(*) as numberEmail FROM formation_users WHERE email :?");
 $req->execute(array($email));
  
 while($email_verification = $req->fetch()) {

  if ($email_verification['numberEmail'] != 0) {
   header('location: ./?error=1&email=1');
   exit;
  }
 }

 // HASH
 $secret = sha1($email.time());
 $secret = sha1($secret).time().time();

 // Crypter le mot de passe ('grain de sel')
 $password = "a1d".sha1($password."2584")."25";

 // Insérer les données dans la base de données
 $req = $db->prepare("INSERT INTO users(pseudo, email, password, secret) VALUES(?, ?, ?, ?)");
 $req->execute(array($pseudo, $email, $password, $secret));

 header('location: ./?success=1');
 exit;

}
?>

<!DOCTYPE html>
<html lang="fr">
 <head>
  <meta charset="UTF-8">
  <title>ESPACE INSCRIPTION</title>
  <link rel="stylesheet" type="text/css" href="design/default.css">
 </head>

 <body>
  <header>
   <h1>INSCRIPTION</h1>
  </header>
 
  <div class="container">
   <?php
    if (!isset($_SESSION['connect'])) {
   ?>

   <h2>Bienvenue sur le site. Pour en voir plus, inscrivez-vous.</h2>

   <?php 
   // Notifier à l'utilisateur la différence de mots de passe
    if (isset($_GET['error'])) {
     if ($_GET['pass']) {
      echo '<p id="error">Les mots de passe ne sont pas identiques.</p>';
     
     } // ou un compte email déjà inscrit
     else if (isset($_GET['email'])) {
      echo '<p id="error">Un compte existe déjà avec cet email.</p>';
     }  
    } // Message de succès
     else if (isset($_GET['success'])) {
     echo '<p id="success">Votre compte a été créé. Vous pouvez vous connecter.</p>';
    }
   
   ?>

   <form action="index.php" method="post">
    <table>
     <tr>
      <td>Pseudo</td>
      <td><input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" required></td>
     </tr>

     <tr>
      <td>Email</td>
      <td><input type="email" name="email" id="email" placeholder="example@google.com" required></td>
     </tr>

     <tr>
      <td>Mot de passe</td>
      <td><input type="password" name="password" id="mdp" placeholder="********" required></td>
     </tr>

     <tr>
      <td>Confirmation du mot de passe</td>
      <td><input type="password" name="password_confirm" id="mdp2" placeholder="********" required></td>
     </tr>    
    </table>
    
    <button> Inscription</button>
   </form>

   <br>
   <i>Si vous avez déjà un compte, connectez-vous.</i>
   <br>
   <button>
   <a href="connexion.php">Connexion</a>
   </button>
  </div>

  <?php } else { ?>
   <p id="info">Bonjour <?php echo $_SESSION['pseudo']; }?><br>
   <a href="deconnexion.php">Déconnexion</a>
  </p>


 
 </body>
</html>