<?php
session_start();

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
     echo "<script>
        alert('Veuillez vous connecter');
        window.location.href = 'rdv.php';
    </script>";}

// Connexion aux bases de données
try {
    // Base 'prisederendezvous' pour les RDV
    $pdo = new PDO("mysql:host=localhost;dbname=prisederendezvous;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Base 'coach' pour récupérer l'id du coach
    $pdoCoach = new PDO("mysql:host=localhost;dbname=coach;charset=utf8", "root", "");
    $pdoCoach->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

// Récupérer les données du formulaire (sécuriser avec htmlspecialchars si affiché)
$coach = $_POST['coach'] ?? '';
$jour = $_POST['jour'] ?? '';
$heure = $_POST['heure'] ?? '';
$client = $_SESSION['nomprenom'] ?? '';
$id_utilisateur = $_SESSION['id_utilisateur'] ?? '';

if (empty($coach) || empty($jour) || empty($heure)) {
    echo "<script>
        alert('Veuillez remplir tous les champs.');
        window.location.href = 'rdv.php';
    </script>";
    exit;
}

// Récupérer l'id du coach depuis son nom
$sqlCoach = "SELECT id_coach FROM info_coach WHERE nom = ?";
$stmtCoach = $pdoCoach->prepare($sqlCoach);
$stmtCoach->execute([$coach]);
$coachData = $stmtCoach->fetch(PDO::FETCH_ASSOC);

if (!$coachData) {
    echo "<script>
        alert('Coach introuvable.');
        window.location.href = 'rdv.php';
    </script>";
    exit;
}
$id_coach = $coachData['id_coach'];

// Vérifier que le créneau (id_coach + jour + heure) n'est pas déjà pris par quelqu'un
$sqlCreneau = "SELECT COUNT(*) FROM rendezvous WHERE id = ? AND jour = ? AND heure = ?";
$stmtCreneau = $pdo->prepare($sqlCreneau);
$stmtCreneau->execute([$id_coach, $jour, $heure]);
if ($stmtCreneau->fetchColumn() > 0) {
    echo "<script>
        alert('Ce créneau est déjà pris.');
        window.location.href = 'rdv.php';
    </script>";
    exit;
}

// Insérer le rendez-vous
$sqlInsert = "INSERT INTO rendezvous (id, coach, jour, heure, client, id_utilisateur) VALUES (?, ?, ?, ?, ?, ?)";
$stmtInsert = $pdo->prepare($sqlInsert);
$success = $stmtInsert->execute([$id_coach, $coach, $jour, $heure, $client, $id_utilisateur]);

if ($success) {
    echo "<script>
        alert('Rendez-vous pris avec succès.');
        window.location.href = 'rdv.php';
    </script>";
} else {
    echo "Erreur lors de la prise de rendez-vous.";
}
?>
