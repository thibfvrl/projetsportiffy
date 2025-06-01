<?php
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
        exit();
    } else {
        //  Identifiants incorrects → message d'erreur + retour
        echo "<script>alert('Identifiant ou mot de passe incorrect'); window.history.back();</script>";
    }
} else {
    echo "<h1>Base de données non trouvée.</h1>";
}

mysqli_close($db_handle);
?>
