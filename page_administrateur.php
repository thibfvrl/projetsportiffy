<?php
session_start();
$nom = isset($_SESSION["nom"]) ? $_SESSION["nom"] : "Administrateur";
$prenom = isset($_SESSION["prenom"]) ? $_SESSION["prenom"] : "";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Admin - Sportify</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
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

    main {
      max-width: 900px;
      margin: 80px auto;
      padding: 20px;
      text-align: center;
    }

    h1 {
      font-size: 2.5rem;
      color: #111;
      margin-bottom: 30px;
    }

    .btn-container {
      display: flex;
      justify-content: center;
      gap: 40px;
      margin-top: 40px;
    }

    .btn {
      background-color: #68d391;
      color: white;
      padding: 15px 30px;
      font-size: 1.1rem;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      text-decoration: none;
      font-weight: bold;
    }

    .btn:hover {
      background-color: #4caf50;
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

    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 60px;
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
<?php endif; ?>
 <div class="Deconnexion" style="position: absolute; right: 100px;">

  <a href="Deconnexion.php" style="color: white; font-weight: bold; text-decoration: none;">Déconnexion</a>
</div>
    </nav>
  </header>

<main>
  <h1>Bienvenue <?php echo htmlspecialchars($prenom . " " . $nom); ?> !</h1>

  <div class="btn-container">
    <a href="ajouter_coach.php" class="btn">Ajouter un coach</a>
    <a href="liste_coachs.php" class="btn">Liste des coachs</a>
  </div>
</main>

<footer>
  <p>&copy; 2025 Sportify</p>
</footer>

</body>
</html>
