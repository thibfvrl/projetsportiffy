<?php 


$database = "informationsclients";
$db_handle = mysqli_connect('localhost', 'root', '');
$db_found = mysqli_select_db($db_handle, $database);

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['id_utilisateur'])) {
    echo "<script>window.location.href = 'connexionClient.php';</script>";
    exit();
}

$id = mysqli_real_escape_string($db_handle, $_SESSION['id_utilisateur']);

if ($db_found) {
    $sql = "SELECT * FROM utilisateur WHERE id_utilisateur='$id'";
    $result = mysqli_query($db_handle, $sql);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        // On stocke le nom et prénom (colonne "nom prenom") dans la session
        $_SESSION['nomprenom'] = $row['nomprenom']; // attention : bien respecter le nom exact de la colonne
    }
}
?>
