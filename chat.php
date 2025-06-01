<?php
session_start();
include("connexion.php");

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}

$mon_id = $_SESSION['id'];
$destinataire_id = isset($_GET['destinataire']) ? intval($_GET['destinataire']) : null;

if ($destinataire_id) {
    $stmt = $db->prepare("SELECT * FROM utilisateurs WHERE id = ?");
    $stmt->execute([$destinataire_id]);
    $destinataire = $stmt->fetch();
    if (!$destinataire) {
        echo "Destinataire introuvable.";
        exit;
    }
} else {
    echo "<h2>Choisissez un destinataire :</h2><ul>";
    $users = $db->prepare("SELECT * FROM utilisateurs WHERE id != ?");
    $users->execute([$mon_id]);
    while ($u = $users->fetch()) {
        echo "<li><a href='?destinataire={$u['id']}'>{$u['nom']} ({$u['type']})</a></li>";
    }
    echo "</ul><a href='?logout=true'>Se déconnecter</a>";
    exit;
}

if (isset($_POST['message'])) {
    $texte = htmlspecialchars($_POST['message']);
    $stmt = $db->prepare("INSERT INTO messages (expediteur_id, destinataire_id, message) VALUES (?, ?, ?)");
    $stmt->execute([$mon_id, $destinataire_id, $texte]);
    header("Location: chat.php?destinataire=$destinataire_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Chat</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div id="wrapper">
    <div id="menu">
        <p class="welcome">Connecté en tant que <b><?= $_SESSION['name'] ?></b></p>
        <p class="logout"><a href='?logout=true'>Déconnexion</a></p>
    </div>

    <h2>Conversation avec <?= htmlspecialchars($destinataire['nom']) ?> (<?= $destinataire['type'] ?>)</h2>

    <div id="chatbox">
        <?php
        $stmt = $db->prepare("SELECT * FROM messages WHERE (expediteur_id = :me AND destinataire_id = :them) OR (expediteur_id = :them AND destinataire_id = :me) ORDER BY date_envoi ASC");
        $stmt->execute(['me' => $mon_id, 'them' => $destinataire_id]);
        while ($msg = $stmt->fetch()) {
            $auteur = ($msg['expediteur_id'] == $mon_id) ? "Moi" : htmlspecialchars($destinataire['nom']);
            echo "<div class='msgln'><b>$auteur :</b> " . htmlspecialchars($msg['message']) . " <span class='chat-time'>{$msg['date_envoi']}</span></div>";
        }
        ?>
    </div>

    <form method="post">
        <input name="message" type="text" id="usermsg" required>
        <input type="submit" value="Envoyer" id="submitmsg">
    </form>
</div>
</body>
</html>
