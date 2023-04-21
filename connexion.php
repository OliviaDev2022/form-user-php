<?php
// PROJET ESPACE MEMBRE - page connexion
session_start();

if(isset($_SESSION['connect'])) {
 header('location: ./index.php');
 exit;
}

require ('src/bdd.php');

if (!empty($_POST['email']) && !empty($_POST['password'])) {
 $email = $_POST['email'];
 $password = $_POST['password'];
 $error = 1;

 // Crypter le mot de passe 
 $password = "a1d".sha1($password."2584")."25";

  // Vérifier si l'email et le mot de passe sont dans la base de données
 $req = $db->prepare("SELECT * FROM users WHERE email = ?");
 $req->execute(array($email));

 while ($user = $req->fetch()) {  
  //pour récupérer une ligne à la fois sous forme de tableau
  if ($password == $user['password']) {
   $error = 0;
   $_SESSION['connect'] = 1;
   $_SESSION['pseudo'] = $user['pseudo'];
   
   header('location: ./connexion.php?success=1');
   exit;
  } 

  if($error == 1) {
   header('location: ./connexion.php?error=1');
   exit;
  }
 }
}   
 



?>

<!DOCTYPE html>
<html lang="fr">
 <head>
  <meta charset="UTF-8">
  <title>ESPACE CONNEXION</title>
  <link rel="stylesheet" type="text/css" href="../ESPACE MEMBRE/design/default.css">
 </head>

<body>
 <header>
  <h1>CONNEXION</h1>
 </header>

 <div class="container">
  <h2>Bienvenue sur le site. Connectez-vous</h2>
 
  <?php
  
  if (isset($_GET['error'])) {
   echo '<p id="error">Email ou mot de passe incorrect</p>';
  }
  else if (isset($_GET['success'])) {
   echo '<p id="success">Vous êtes maintenant connecté</p>';
  }

  ?>

  <form action="connexion.php" method="post">
   <table>
    <tr>
    <td>Email</td>
    <td><input type="email" name="email" id="email" placeholder="example@google.com" required></td>
    </tr>

    <tr>
     <td>Mot de passe</td>
     <td><input type="password" name="password" id="mdp" placeholder="********" required></td>
    </tr>
   </table>

   <!-- 
    <p><label><input type="checkbox" name="connect" checked>Connexion automatique</label></p>
   -->
   
   <button> Connexion</button>
  </form>

  <br>
  <i>Si vous n'êtes pas encore inscrit, inscrivez-vous.</i>
  <br>
  <button>
  <a href="index.php">Inscription</a>
  </button>

 </div>


</body>
</html>