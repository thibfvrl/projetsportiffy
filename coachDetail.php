<?php
session_start();
// Vérifie la présence de l'id dans l'URL
if (!isset($_GET['id'])) {
    die("ID coach manquant.");
}

$coachId = intval($_GET['id']);
$discipline = isset($_GET['discipline']) ? $_GET['discipline'] : null;

// Connexion à la BDD coach
try {
    $pdoCoach = new PDO("mysql:host=localhost;dbname=coach;charset=utf8", "root", "");
    $pdoCoach->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur BDD coach : " . $e->getMessage());
}

// Connexion à la BDD rendez-vous
try {
    $pdoRdv = new PDO("mysql:host=localhost;dbname=prisederendezvous;charset=utf8", "root", "");
    $pdoRdv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur BDD rendez-vous : " . $e->getMessage());
}

// Récupération des infos du coach
$stmt = $pdoCoach->prepare("SELECT * FROM info_coach WHERE id_coach = ?");
$stmt->execute([$coachId]);
$coach = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$coach) {
    die("Coach non trouvé.");
}

// Récupération du nom du coach (pour les rendez-vous)
$nomCoach = $coach['nom'];

// Jours et heures pour l'emploi du temps
$jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
$heures = ["09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h"];

// Récupération des créneaux réservés dans la BDD rdv
$creneaux = [];
$req = $pdoRdv->prepare("SELECT jour, heure FROM rendezvous WHERE coach = ?");
$req->execute([$nomCoach]);
while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
    // clé unique jour+heure pour repérer les réservations
    $key = $ligne['jour'] . $ligne['heure'];
    $creneaux[$key] = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Détails du coach - <?= htmlspecialchars($coach['nom']) ?></title>
    <style>
        h1{
            text-align: center;

        }
        h2{
            text-align: center;

        }
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
      position: relative;
      height: 60px;
      background-color: black;
      padding: 0 40px;
      display: flex;
      align-items: center;
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

    footer {
        margin-top: 100px;
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
      position: fixed;
      width: 100%;
      bottom: 0;
    }

    main {
      max-width: 900px;
      margin: 80px auto 80px;
      padding: 0 20px;
      text-align: center;
    }

    h1 {
      color: #68d391;
      margin-bottom: 20px;
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

       
        table { 
            border-collapse: collapse; 
            width: 100%; 
            margin-bottom: 30px; 
        }
        th, td { 
            border: 1px solid #ccc;
             padding: 8px;
              text-align: center; 
          }
        th { 
            background-color: #eee; 
        }
        .dispo { 
            background-color: #68d391; 
        }   
        .indispo { 
            background-color: #a0aec0; 
        } 
        .btn-retour { 
            display: inline-block; 
            margin-bottom: 20px; 
            text-decoration: none; 
            background: #68d391; 
            color: white; padding: 8px 15px; 
            
        }
        .btn-retour:hover { 
            background: #68d391; 
        }
        img.coach-photo { 
            max-width: 200px; 
            height: auto; 
            margin-bottom: 20px; 
            border-radius: 5px; 
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
    <!-- Ces liens ne sont visibles que pour les utilisateurs (pas les coachs) -->
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

<?php if ($discipline): ?>
  <a href="coachCompetition.php?discipline=<?= urlencode($discipline) ?>" class="btn-retour">⬅ Retour à la liste des coachs</a>
<?php else: ?>
  <a href="coachCompetition.php" class="btn-retour"> Retour à la liste des coachs</a>
<?php endif; ?>

<h1>Détails du coach : <?= htmlspecialchars($coach['nom']) ?></h1>

<table>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Spécialité</th>
        <th>Photo</th>
        <th>Vidéo</th>
        <th>CV</th>
        <th>Disponibilités</th>
        <th>Email</th>
        <th>Compétition</th>
        <th>Salle</th>
    </tr>
    <tr>
        <td><?= htmlspecialchars($coach['id_coach']) ?></td>
        <td><?= htmlspecialchars($coach['nom']) ?></td>
        <td><?= htmlspecialchars($coach['specialite']) ?></td>
        <td>
            <?php if (!empty($coach['photo'])): ?>
                <img src="images/<?= htmlspecialchars($coach['photo']) ?>" alt="Photo de <?= htmlspecialchars($coach['nom']) ?>" class="coach-photo">
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($coach['video'])): ?>
                <a href="<?= htmlspecialchars($coach['video']) ?>" target="_blank">Voir vidéo</a>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
        <td>
            <?php if (!empty($coach['cv'])): ?>
                <a href="cv/<?= htmlspecialchars($coach['cv']) ?>" target="_blank">Voir CV</a>
            <?php else: ?>
                N/A
            <?php endif; ?>
        </td>
        <td><?= htmlspecialchars($coach['dispo']) ?></td>
        <td><?= htmlspecialchars($coach['adresse_mail']) ?></td>
        <td><?= $coach['competition'] ? 'Oui' : 'Non' ?></td>
        <td><?= htmlspecialchars($coach['salle']) ?></td>
    </tr>
</table>

<h2>Emploi du temps de <?= htmlspecialchars($nomCoach) ?></h2>

<table>
    <tr>
        <th>Jour</th>
        <?php foreach ($heures as $heure): ?>
            <th><?= htmlspecialchars($heure) ?></th>
        <?php endforeach; ?>
    </tr>

    <?php foreach ($jours as $jour): ?>
        <tr>
            <td><?= htmlspecialchars($jour) ?></td>
            <?php foreach ($heures as $heure): 
                $key = $jour . $heure;
                $reserved = isset($creneaux[$key]);
            ?>
                <td class="<?= $reserved ? 'indispo' : 'dispo' ?>">
                    <?= $reserved ? 'X' : '' ?>
                </td>
            <?php endforeach; ?>
        </tr>
    <?php endforeach; ?>
</table>


</body>

</html>
