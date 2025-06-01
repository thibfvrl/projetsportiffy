<?php



session_start();

if (isset($_SESSION['id_utilisateur'])) {
    require_once 'AuthentificationClient.php';
}
try {
  $pdoCoach = new PDO("mysql:host=localhost;dbname=coach;charset=utf8", "root", "");
  $pdoCoach->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $stmt = $pdoCoach->query("SELECT nom FROM info_coach");
  $coachs = $stmt->fetchAll(PDO::FETCH_ASSOC);

  $pdoRdv = new PDO("mysql:host=localhost;dbname=prisederendezvous;charset=utf8", "root", "");
  $pdoRdv->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
  die("Erreur de connexion : " . $e->getMessage());
}

$jours = ["Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi", "Dimanche"];
$heures = ["09h", "10h", "11h", "12h", "13h", "14h", "15h", "16h", "17h", "18h", "19h", "20h"];

$creneauxReserves = [];
$req = $pdoRdv->query("SELECT coach, jour, heure FROM rendezvous");
while ($ligne = $req->fetch(PDO::FETCH_ASSOC)) {
  $cle = $ligne['coach'] . '_' . $ligne['jour'] . '_' . $ligne['heure'];
  $creneauxReserves[$cle] = true;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Prendre un RDV - Sportify</title>
  <style>
    body {
      margin: 0; padding: 0;
      font-family: Arial, sans-serif;
      line-height: 1.6;
      background-color: #fff;
      color: #111;
    }

    /* HEADER & NAVBAR */
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

    /* SESSION USER DISPLAY */
    .user-greeting {
      position: absolute;
      right: 40px;
      color: white;
      font-weight: bold;
    }
    .user-greeting a {
      color: white;
      text-decoration: none;
    }
    .user-greeting a:hover {
      text-decoration: underline;
    }

    /* MAIN TITLE */
    .titre {
      text-align: center;
      padding: 20px 20px;
      background-color: #68d391;
      color: black;
    }
    .titre h1 {
      font-size: 2.5rem;
      margin-bottom: 10px;
    }

    /* FORM CONTAINER */
    .form-container {
      max-width: 700px;
      margin: 40px auto;
      padding: 30px;
      background-color: #f0f0f0;
      border: 5px solid #68d391;
      border-radius: 10px;
    }
    .form-container label {
      display: block;
      margin: 15px 0 5px;
      font-weight: bold;
    }
    .form-container select,
    .form-container input {
      width: 100%;
      padding: 10px;
      font-size: 1rem;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .form-container button {
      padding: 12px 20px;
      background-color: #68d391;
      color: white;
      border: none;
      font-weight: bold;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
    }
    .form-container button:hover {
      background-color: #4caf50;
    }

    /* DISPO SECTION */
    .dispo-section {
      margin: 40px auto;
      max-width: 1000px;
      text-align: center;
    }
    .coach-btn {
      padding: 10px 20px;
      margin: 10px;
      background-color: #68d391;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }
    .coach-btn:hover {
      background-color: #48b37e;
    }
    .dispo-table-wrapper {
      margin-top: 20px;
      overflow-x: auto;
    }
    .dispo-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      min-width: 700px;
    }
    .dispo-table th, .dispo-table td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: center;
    }
    .dispo-table th {
      background-color: #68d391;
      color: white;
    }
    .reserved {
      background-color: #a0aec0;
      color: white;
    }

    /* FOOTER */
    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
    }
  </style>
  <script>
    function toggleDispo(coachId) {
      const sections = document.querySelectorAll('.dispo-table-wrapper');
      sections.forEach(sec => sec.style.display = 'none');
      document.getElementById('table_' + coachId).style.display = 'block';
    }
  </script>
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
    <li> <a href="chat.php">Messagerie </a></li>

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

  <main>
    <section class="titre">
      <h1>Prendre un Rendez-vous</h1>
    </section>

    <section class="form-container">
      <form action="valider_rdv.php" method="POST">
        <label for="coach">Coach :</label>
        <select id="coach" name="coach" required>
          <option value="">-- Sélectionner --</option>
          <?php foreach ($coachs as $c): ?>
            <option value="<?= htmlspecialchars($c['nom']); ?>"><?= htmlspecialchars($c['nom']); ?></option>
          <?php endforeach; ?>
        </select>

        <label for="jour">Jour :</label>
        <select id="jour" name="jour" required>
          <option value="">-- Sélectionner un jour --</option>
          <?php foreach ($jours as $jour): ?>
            <option value="<?= $jour; ?>"><?= $jour; ?></option>
          <?php endforeach; ?>
        </select>

        <label for="heure">Heure :</label>
        <select id="heure" name="heure" required>
          <option value="">-- Sélectionner une heure --</option>
          <?php foreach ($heures as $heure): ?>
            <option value="<?= $heure; ?>"><?= $heure; ?></option>
          <?php endforeach; ?>
        </select>

        <button type="submit">Confirmer le rendez-vous</button>
      </form>
    </section>

    <section class="dispo-section">
      <h2>Voir les disponibilités</h2>
      <?php foreach ($coachs as $c): 
        $id = strtolower(str_replace(' ', '_', $c['nom'])); ?>
        <button class="coach-btn" onclick="toggleDispo('<?= $id; ?>')">
          <?= htmlspecialchars($c['nom']); ?>
        </button>
      <?php endforeach; ?>

      <?php foreach ($coachs as $c): 
        $id = strtolower(str_replace(' ', '_', $c['nom'])); ?>
        <div id="table_<?= $id; ?>" class="dispo-table-wrapper" style="display:none;">
          <h3>Disponibilités de <?= htmlspecialchars($c['nom']); ?></h3>
          <table class="dispo-table">
            <thead>
              <tr>
                <th>Jour</th>
                <?php foreach ($heures as $heure): ?>
                  <th><?= $heure; ?></th>
                <?php endforeach; ?>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($jours as $jour): ?>
                <tr>
                  <td><?= $jour; ?></td>
                  <?php foreach ($heures as $heure): 
                    $key = $c['nom'] . '_' . $jour . '_' . $heure;
                    $reserved = isset($creneauxReserves[$key]); ?>
                    <td class="<?= $reserved ? 'reserved' : '' ?>">
                      <?= $reserved ? 'Réservé' : 'Libre' ?>
                    </td>
                  <?php endforeach; ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      <?php endforeach; ?>
    </section>
  </main>

  <footer>
    <p>© 2025 SPORTIFY - Tous droits réservés</p>
  </footer>
</body>
</html>

