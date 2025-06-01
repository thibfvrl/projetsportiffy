<?php
session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header("Location: connexionClient.php");
    exit();
}

if (!isset($_POST['rdv_id'])) {
    header("Location: compte.php");
    exit();
}

$idUtilisateur = intval($_SESSION['id_utilisateur']);
$idRdv = intval($_POST['rdv_id']);

$conn = new mysqli("localhost", "root", "", "prisederendezvous");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

var_dump($idRdv, $idUtilisateur);

$sql = "DELETE FROM rendezvous WHERE id_rdv = ? AND id_utilisateur = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $idRdv, $idUtilisateur);
$stmt->execute();


$stmt->close();
$conn->close();

header("Location: espaceclient.php");
exit();
?>
