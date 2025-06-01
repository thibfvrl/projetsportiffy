<?php
session_start();

$database = "informationsclients";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

if (!$db_found) {
    die("Base de données non trouvée.");
}

// Récupère l'id utilisateur stocké en session
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non identifié. Merci de créer un compte d'abord.");
}

$id_utilisateur = $_SESSION['id_utilisateur'];

$adresse = isset($_POST["adresse"]) ? trim($_POST["adresse"]) : "";
$ville = isset($_POST["ville"]) ? trim($_POST["ville"]) : "";
$codepostal = isset($_POST["codepostal"]) ? trim($_POST["codepostal"]) : "";
$pays = isset($_POST["pays"]) ? trim($_POST["pays"]) : "";
$telephone = isset($_POST["telephone"]) ? trim($_POST["telephone"]) : "";

// On ne récupère plus carteetudiant du POST directement (c’est un fichier)
$carteetudiant_path = ""; // chemin de l'image à stocker en base

if (isset($_POST["ajouter"])) {
    // Gestion upload image carte étudiant
    if (isset($_FILES['carteetudiant_img']) && $_FILES['carteetudiant_img']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['carteetudiant_img']['tmp_name'];
        $fileName = $_FILES['carteetudiant_img']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $allowedfileExtensions = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileExtension, $allowedfileExtensions)) {
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            $uploadFileDir = './carteetudiant_img/';

            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            if (move_uploaded_file($fileTmpPath, $dest_path)) {
                $carteetudiant_path = $dest_path; // chemin relatif à stocker en DB
            } else {
                die("Erreur lors du déplacement du fichier.");
            }
        } else {
            die("Type de fichier non autorisé. Extensions autorisées : jpg, jpeg, png, gif.");
        }
    } else {
        die("Erreur d'upload ou aucun fichier sélectionné.");
    }

    // Prépare et sécurise les données avant insertion
    $adresse = mysqli_real_escape_string($db_handle, $adresse);
    $ville = mysqli_real_escape_string($db_handle, $ville);
    $codepostal = mysqli_real_escape_string($db_handle, $codepostal);
    $pays = mysqli_real_escape_string($db_handle, $pays);
    $telephone = mysqli_real_escape_string($db_handle, $telephone);
    $carteetudiant_path = mysqli_real_escape_string($db_handle, $carteetudiant_path);

    $insert_sql = "INSERT INTO informationspersonnelles 
        (id_utilisateur, pays, codepostal, ville, adresse, telephone, carteetudiant) 
        VALUES ('$id_utilisateur', '$pays', '$codepostal', '$ville', '$adresse', '$telephone', '$carteetudiant_path')";

    $result = mysqli_query($db_handle, $insert_sql);

    if ($result) {
        header("Location: connexionClient.php");
        exit();
    } else {
        echo "Erreur lors de l'insertion des informations personnelles : " . mysqli_error($db_handle);
    }
}

mysqli_close($db_handle);
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Informations client - Sportify</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    body { margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #ffffff; color: #111; }
    header { background-color: black; padding: 20px 0; }

    .navbar { height: 60px; background-color: black; padding: 0 40px; display: flex; align-items: center; position: relative; }

    .logo { display: flex; align-items: center; gap: 10px; }

    .logo img { height: 40px; }

    .logo span { color: white; font-weight: bold; font-size: 1.2rem; }

    .nav-links { position: absolute; left: 50%; transform: translateX(-50%); display: flex; gap: 30px; list-style: none; margin: 0; padding: 0; }

    .nav-links a { text-decoration: none; color: white; font-weight: bold; transition: color 0.3s; }

    .nav-links a:hover { color: #68d391; }
    .nav-links .dropdown { position: relative; }
    .nav-links .dropdown-content { display: none; position: absolute; top: 30px; background-color: black; padding: 10px 0; border-radius: 5px; z-index: 10; min-width: 150px; }
    .nav-links .dropdown:hover .dropdown-content { display: block; }
    .nav-links .dropdown-content a { display: block; padding: 10px 20px; color: white; text-decoration: none; white-space: nowrap; }
    .nav-links .dropdown-content a:hover { background-color: #68d391; color: black; }
    .backgroundsup { padding: 20px; height: 400px; background-color: #68d391; text-align: center; }
    h1 { margin-top: 140px; font-weight: bold; font-size: 60px; color: #ffffff; }
    main { max-width: 900px; margin: 80px auto; padding: 20px; text-align: center; }
    .chevauche-bloc { background-color: white; padding: 40px; width: 500px; max-width: 95%; margin: -300px auto 0; position: relative; z-index: 5; text-align: left; }
    .chevauche-bloc h2 { margin-bottom: 20px; font-size: 24px; }
    .chevauche-bloc form { display: flex; flex-direction: column; gap: 15px; }
    .chevauche-bloc input, .chevauche-bloc select { padding: 10px; border: 1px solid #ccc; width: 100%; }
    .chevauche-bloc button { background-color: #cecece; color: black; padding: 10px; border: none; font-weight: bold; cursor: pointer; transition: background-color 0.3s; }
    .chevauche-bloc button:hover { background-color: #a0a0a0; }
    footer { background-color: black; color: white; text-align: center; padding: 15px 0; margin-top: 40px; width: 100%; }
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
            <li><a href="sport.php">Sports de compétition</a></li>
            <li><a href="salle.php">Salle de sport Omnes</a></li>
          </ul>
        </li>
        <li><a href="recherche.php">Recherche</a></li>
        <li><a href="rdv.php">Rendez-vous</a></li>
        <li><a href="compte.php">Votre Compte</a></li>
      </ul>
    </nav>
  </header>

  <section class="backgroundsup">
    <h1>INFORMATIONS CLIENT</h1>
  </section>

  <main>
    <div class="chevauche-bloc">
      <form action="informationspersonnelles.php" method="post" enctype="multipart/form-data">
        <h2>Informations personnelles</h2>
        <input type="text" name="adresse" placeholder="Adresse" required />
        <input type="text" name="ville" placeholder="Ville" required />
        <input type="text" name="codepostal" placeholder="Code Postal" required />
        <input type="text" name="pays" placeholder="Pays" required />
        <input type="tel" name="telephone" placeholder="Numéro de téléphone" required />
        <input type="file" name="carteetudiant_img" placeholder="Carte Étudiant" accept="image/*" required />
        <button type="submit" name="ajouter">Valider</button>
      </form>
    </div>
  </main>

  <footer>
    <p>&copy; 2025 Sportify</p>
  </footer>
</body>
</html>
