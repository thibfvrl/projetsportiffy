<?php
session_start();

// Connexion à la BDD via PDO
try {
    $pdoCoach = new PDO("mysql:host=localhost;dbname=coach;charset=utf8", "root", "");
    $pdoRdv = new PDO("mysql:host=localhost;dbname=prisederendezvous;charset=utf8", "root", "");
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
    exit;
}

// Récupération de tous les coachs "cours-collectif"
$coachs = [];
$stmt = $pdoCoach->prepare("SELECT * FROM info_coach WHERE specialite = 'cours-collectif'");
$stmt->execute();
$coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Jours et heures
$jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
$heures = ["09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h"];
?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Sportify</title>
  <link rel="stylesheet" href="style.css" />
  <style>
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

    .background-muscu {
      background-image: url("coach/fitness.jpeg"); /* adapte le chemin */
      background-size: cover;
      background-position: center;
      background-attachment: fixed;
      min-height: calc(100vh - 120px);
      padding: 60px 20px;
      display: flex;
      justify-content: center;
      align-items: flex-start;
    }

    .fiche-coach {
      background-color: #f2f2f2;
      border-radius: 25px;
      padding: 30px;
      max-width: 900px;
      width: 100%;
      box-shadow: 0 4px 16px rgba(0,0,0,0.15);
      text-align: center;
      border: 2px solid #68d391;
    }

    .fiche-coach-contenu {
      display: flex;
      align-items: center;
      justify-content: center;
      flex-wrap: wrap;
      gap: 30px;
      margin-bottom: 30px;
    }

    .coach-photo {
      width: 180px;
      height: auto;
      border-radius: 12px;
    }

    .coach-details h2 {
      margin: 0;
      color: #222;
    }

    .coach-details h3 {
      margin: 5px 0;
      color: #666;
    }

    .coach-horaires {
      margin-bottom: 30px;
    }

    .planning-table {
      width: 100%;
      border-collapse: collapse;
    }

    .planning-table th,
    .planning-table td {
      border: 1px solid #aaa;
      padding: 10px;
      text-align: center;
    }

    .planning-table th {
      background-color: #e2e8f0;
      font-weight: bold;
    }

    .coach-actions {
      display: flex;
      justify-content: center;
      gap: 20px;
      flex-wrap: wrap;
    }

    .btn-green {
      background-color: #c6f6d5;
      color: #111;
      font-weight: bold;
      padding: 12px 20px;
      border: 2px solid #68d391;
      border-radius: 12px;
      cursor: pointer;
    }

    .btn-blue {
      background-color: #bee3f8;
      color: #111;
      font-weight: bold;
      padding: 12px 20px;
      border: 2px solid #4299e1;
      border-radius: 12px;
      cursor: pointer;
    }

    .btn-gray {
      background-color: #e2e8f0;
      color: #111;
      font-weight: bold;
      padding: 12px 20px;
      border: 2px solid #a0aec0;
      border-radius: 12px;
      cursor: pointer;
    }

    .btn-green:hover {
      background-color: #68d391;
      color: white;
    }

    .btn-blue:hover {
      background-color: #4299e1;
      color: white;
    }

    .btn-gray:hover {
      background-color: #a0aec0;
      color: white;
    }

    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      width: 100%;
      position: fixed;
      bottom: 0;
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
        <li><a href="recherche.php">Recherche</a></li>
        <li><a href="rdv.php">Rendez-vous</a></li>
        <li><a href="compte.php">Votre Compte</a></li>
      </ul>
    </nav>
  </header>
<?php
$database = "Coach";
$db_handle = mysqli_connect('localhost', 'root', '', $database);

$coachs = [];

if ($db_handle) {
    $sql = "SELECT * FROM info_coach WHERE specialite = 'musculation'";
    $result = mysqli_query($db_handle, $sql);

    while ($row = mysqli_fetch_assoc($result)) {
        $coachs[] = $row;
    }

    mysqli_close($db_handle);
} else {
    echo "Erreur de connexion à la base de données.";
}
?>

<main class="background-muscu">
  <?php foreach ($coachs as $coach): ?>
    <?php
    // Créneaux réservés pour ce coach
    $creneaux = [];
    $req = $pdoRdv->prepare("SELECT jour, heure FROM rendezvous WHERE coach = ?");
    $req->execute([$coach['nom']]);
    while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
        $creneaux[$ligne['jour'] . $ligne['heure']] = true;
    }
    ?>

    <div class="fiche-coach">
      <div class="fiche-coach-contenu">
        <img src="<?= htmlspecialchars($coach['photo']) ?>" alt="Photo de <?= htmlspecialchars($coach['nom']) ?>" class="coach-photo">
        <div class="coach-details">
          <h2><?= htmlspecialchars($coach['nom']) ?></h2>
          <h3>Coach, <?= htmlspecialchars($coach['specialite']) ?></h3>
          <p><strong>Salle :</strong> <?= htmlspecialchars($coach['salle']) ?></p>
          <p><strong>Email :</strong> <?= htmlspecialchars($coach['adresse_mail']) ?></p>
        </div>
      </div>

      <table class="planning-table">
        <thead>
          <tr>
            <th>Jour</th>
            <?php foreach ($heures as $heure): ?>
              <th><?= $heure ?></th>
            <?php endforeach; ?>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($jours as $jour): ?>
            <tr>
              <td><?= $jour ?></td>
              <?php foreach ($heures as $heure):
                $key = $jour . $heure;
                $reserved = isset($creneaux[$key]);
              ?>
                <td class="<?= $reserved ? 'reserve' : 'libre' ?>">
                  <?= $reserved ? 'X' : '' ?>
                </td>
              <?php endforeach; ?>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

      <div class="coach-actions">
        <button class="btn-green" onclick="window.location.href='rdv.php'">Prendre un RDV</button>
        <button class="btn-blue">Communiquer avec le coach</button>
        <button class="btn-gray" onclick="alert('<?= addslashes($coach['cv']) ?>')">Voir son CV</button>
      </div>
    </div>
  <?php endforeach; ?>
</main>


  <footer>
    <p>&copy; 2025 Sportify</p>
  </footer>
</body>
</html>
