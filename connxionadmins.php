<?php
session_start();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifiant = isset($_POST["admin_email"]) ? $_POST["admin_email"] : "";
    $mot_de_passe = isset($_POST["admin_password"]) ? $_POST["admin_password"] : "";

    $database = "administrateurs";
    $db_handle = mysqli_connect('localhost', 'root', '');
    $db_found = mysqli_select_db($db_handle, $database);

    if ($db_found) {
        $sql = "SELECT * FROM admins WHERE identifiant='$identifiant' AND mot_de_passe='$mot_de_passe'";
        $result = mysqli_query($db_handle, $sql);


        if (mysqli_num_rows($result) > 0) {
            
            header("Location: page_administrateur.php");

    $admin = mysqli_fetch_assoc($result);
    // Si une session admin est déjà active, on la réinitialise
if (isset($_SESSION['id_admin'])) {
    session_unset();
    session_destroy();
    session_start(); // redémarrer une nouvelle session propre
}
// Si une session admin est déjà active, on la réinitialise
if (isset($_SESSION['id_client'])) {
    session_unset();
    session_destroy();
    session_start(); // redémarrer une nouvelle session propre
}
// Si une session admin est déjà active, on la réinitialise
if (isset($_SESSION['id_utilisateur'])) {
    session_unset();
    session_destroy();
    session_start(); // redémarrer une nouvelle session propre
}
    $_SESSION['id_admin'] = $admin['id_admin']; // ou le nom exact de la colonne id
    $_SESSION['nom'] = $admin['nom'];           // idem pour la colonne nom
    header("Location: page_administrateur.php");
    exit();

            exit();
        } else {
            echo "<script>alert('Identifiant ou mot de passe incorrect'); window.history.back();</script>";
        }
    } else {
        echo "<h1>Base de données non trouvée.</h1>";
    }

    mysqli_close($db_handle);
}
?>




<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion Administrateur - Sportify</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Le CSS est identique à l'original. Copié pour clarté */

    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      background-color: #ffffff;
      color: #111;
    }

    header {
      background-color: black;
      padding: 20px 0;
    }

    .navbar {
      height: 60px;
      background-color: black;
      padding: 0 40px;
      display: flex;
      align-items: center;
      position: relative;
    }

    .logo {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .logo img {
      height: 40px;
    }

    .logo span {
      color: white;
      font-weight: bold;
      font-size: 1.2rem;
    }

    .nav-links {
      position: absolute;
      left: 50%;
      transform: translateX(-50%);
      display: flex;
      gap: 30px;
      list-style: none;
      margin: 0;
      padding: 0;
    }

    .nav-links a {
      text-decoration: none;
      color: white;
      font-weight: bold;
      transition: color 0.3s;
    }

    .nav-links a:hover {
      color: #68d391;
    }

    .nav-links .dropdown {
      position: relative;
    }

    .nav-links .dropdown-content {
      display: none;
      position: absolute;
      top: 30px;
      background-color: black;
      padding: 10px 0;
      border-radius: 5px;
      z-index: 10;
      min-width: 150px;
    }

    .nav-links .dropdown:hover .dropdown-content {
      display: block;
    }

    .nav-links .dropdown-content a {
      display: block;
      padding: 10px 20px;
      color: white;
      text-decoration: none;
      white-space: nowrap;
    }

    .nav-links .dropdown-content a:hover {
      background-color: #68d391;
      color: black;
    }

    .backgroundsup {
      padding: 20px;
      height: 400px;
      background-color: #68d391;
      color: darkred;
      text-align: center;
    }

    main {
      max-width: 900px;
      margin: 80px auto;
      padding: 20px;
      text-align: center;
      background-color: white;
      color: black;
    }

    h1 {
      margin-top: 180px;
      font-weight: bold;
      font-size: 60px;
      color: #ffffff;
    }

    .chevauche-bloc {
      background-color: white;
      color: black;
      padding: 50px;
      width: 400px;
      max-width: 90%;
      margin: 0 auto;
      margin-top: -250px;
      position: relative;
      z-index: 5;
    }

    .chevauche-bloc h2 {
      margin-top: 0;
      margin-bottom: 20px;
      font-size: 28px;
    }

    .chevauche-bloc form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    .chevauche-bloc input {
      padding: 10px;
      border: 1px solid #ccc;
    }

    .chevauche-bloc button {
      background-color: #cecece;
      color: black;
      padding: 10px;
      border: none;
      font-weight: bold;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .oublieMDP {
      font-style: italic;
    }

    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      width: 100%;
    }
  </style>
</head>
<body>
 <header>
  <nav class="navbar">
    <div class="logo">
      <img src="coach/logo.png" alt="Logo" />
      <span>SPORTIFY</span>
    </div>
    <ul class="nav-links">
      <li><a href="accueil.php">Accueil</a></li>
      <li class="dropdown">
        <a href="#">Tout Parcourir</a>
        <ul class="dropdown-content">
          <li><a href="activite.php">Activités Sportives</a></li>
          <li><a href="compet.php">Sports de compétition</a></li>
          <li><a href="salle.php">Salle de sport Omnes</a></li>
        </ul>
      </li>

      <?php if (!isset($_SESSION['id_coach'])): ?>
        <li><a href="recherche.php">Recherche</a></li>
        <li><a href="rdv.php">Rendez-vous</a></li>
      <?php endif; ?>

      <li><a href="compte.php">Votre Compte</a></li>
    </ul>

    <?php if (isset($_SESSION['id_coach']) && isset($_SESSION['nom'])): ?>
      <div style="position: absolute; left: 1350px; color: white; font-weight: bold;">
        <a href="page_coach.php" style="color: white; text-decoration: none;">
          Bonjour Coach, <?= htmlspecialchars($_SESSION['nom']) ?>
        </a>
      </div>
    <?php elseif (isset($_SESSION['id_utilisateur']) && isset($_SESSION['nomprenom'])): ?>
      <div style="position: absolute; left: 1350px; color: white; font-weight: bold;">
        <a href="espaceClient.php" style="color: white; text-decoration: none;">
          Bonjour, <?= htmlspecialchars($_SESSION['nomprenom']) ?>
        </a>
      </div>
    <?php elseif (isset($_SESSION['id_admin']) && isset($_SESSION['nom'])): ?>
      <div style="position: absolute; left: 1350px; color: white; font-weight: bold;">
        <a href="page_administrateur.php" style="color: white; text-decoration: none;">
          Bonjour Admin, <?= htmlspecialchars($_SESSION['nom']) ?>
        </a>
      </div>
    <?php endif; ?>
  </nav>
</header>
  <section class="backgroundsup">
    <h1>ADMIN - CONNEXION</h1>
  </section>

  <main>
    <div class="chevauche-bloc">
      <h2>Espace Administrateur</h2>
      <form action="seConnecterAdministrateur.php" method="post">
        <input type="text" placeholder="Identifiant administrateur" name="admin_email" required />
        <input type="password" placeholder="Mot de passe" name="admin_password" required />
        <button type="submit">Se connecter</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Sportify</p>
  </footer>
</body>
</html>
