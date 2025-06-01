<?php
session_start();


?>



<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sportify</title>
  <link rel="stylesheet" href="style.css">
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: Arial, sans-serif;
      line-height: 1.6;
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

    .features {
      max-width: 900px;
      margin: 40px auto;
      padding: 30px;
      background-color: #f0f0f0;
      border: 5px solid #68d391;
      border-radius: 10px;
    }

    .features p {
      margin-bottom: 0.7em;
      white-space: pre-line;
    }

    #carrousel {
      position: relative;
      width: 500px;
      height: 300px;
      margin: 40px auto;
      overflow: hidden;
    }

    #carrousel ul {
      padding: 0;
      margin: 0;
    }

    #carrousel img {
  width: 100%;      /* l'image prend toute la largeur du carrousel */
  height: 100%;     /* l'image prend toute la hauteur du carrousel */
  object-fit: cover; /* pour que l'image remplisse le cadre sans déformation */
  border-radius: 12px;
  position: absolute;
  top: 0;
  left: 0;
  display: none;
}


   

    .controls {
      margin-top: 10px;
      display: flex;
      justify-content: space-between;
      width: 500px;
      margin-left: auto;
      margin-right: auto;
    }

    .prev, .next {
      background-color: #68d391;
      color: white;
      padding: 10px 20px;
      cursor: pointer;
      font-weight: bold;
      border: none;
      border-radius: 5px;
    }

    .prev:hover, .next:hover {
      background-color: #4caf50;
    }

    footer {
      background-color: black;
      color: white;
      text-align: center;
      padding: 15px 0;
      margin-top: 40px;
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

<main>
  <section class="titre">
    <h1>"L'expertise sportive à portée de clic"</h1>
  </section>

  <section class="features">
    <p>
      <em> Un accompagnement en deux clics... </em>

      Notre plateforme révolutionne l'accompagnement sportif en rendant l'expertise accessible à tous, partout et à tout moment. Que vous soyez débutant cherchant à adopter un mode de vie actif, athlète amateur désireux de progresser, ou sportif confirmé visant l'excellence, nos coachs certifiés vous accompagnent dans l'atteinte de vos objectifs.

      <em> Une expertise à votre portée... </em>

      Bénéficiez de consultations personnalisées avec des professionnels du sport qualifiés. Nos experts analysent votre profil, vos besoins et vos contraintes pour vous proposer des programmes d'entraînement sur mesure, des conseils nutritionnels adaptés et un suivi régulier de vos performances.

      <em>  Flexibilité et accessibilité... </em>

      Fini les contraintes horaires et géographiques. Consultez nos coachs depuis chez vous, en déplacement ou même entre deux séances à la salle. Programmez vos rendez-vous selon votre emploi du temps et accédez à vos programmes d'entraînement 24h/24.

      <em> Un accompagnement complet...  </em>

      - Consultations vidéo en temps réel avec des coachs spécialisés  
      - Programmes personnalisés adaptés à vos objectifs et contraintes  
      - Suivi de progression avec analyses détaillées de vos performances  
      - Conseils nutritionnels pour optimiser vos résultats  
      - Communauté active pour partager votre parcours et vous motiver  

      Transformez votre potentiel en performance dès aujourd'hui. Votre réussite sportive n'attend que vous...
    </p>
  </section>

        <div id="carrousel">
        <img src="coach/foot.jpeg" >
        <img src="coach/velo.jpg" >
        <img src="coach/basket.jpg" >
        <img src="coach/cardio.jpg" >
        <img src="coach/coursco.jpg" >
        <img src="coach/natation.jpg" >
        <img src="coach/rugby.jpg" >
        <img src="coach/plongeon.jpeg" >
        <img src="coach/tennis.jpeg" >
        <img src="coach/muscu.jpeg" >
        <img src="coach/fitness.jpeg" >
      </div>



  <div class="controls">
    <button class="prev">&#8592;</button>
    <button class="next">&#8594;</button>
  </div>

</main>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script>
  $(document).ready(function(){
    var $carrousel = $('#carrousel'),
    $img = $('#carrousel img'),
    indexImg = $img.length - 1,
    i = 0,
    $currentImg = $img.eq(i);

    $img.css('display', 'none');
    $currentImg.css('display', 'block');

    $('.next').click(function(){
      i = (i < indexImg) ? i + 1 : 0;
      $img.hide();
      $currentImg = $img.eq(i);
      $currentImg.show();
    });

    $('.prev').click(function(){
      i = (i > 0) ? i - 1 : indexImg;
      $img.hide();
      $currentImg = $img.eq(i);
      $currentImg.show();
    });

    function slideImg(){
      setTimeout(function(){
        i = (i < indexImg) ? i + 1 : 0;
        $img.hide();
        $currentImg = $img.eq(i);
        $currentImg.show();
        slideImg();
      }, 4000);
    }

    slideImg();
  });
</script>
<section id="map" style="width:100%; height:400px; margin-top:40px;">
  <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d83998.96769528177!2d2.264633545212908!3d48.85882554203499!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47e66e1f06e2b70f%3A0x40b82c3688c9460!2sParis!5e0!3m2!1sfr!2sfr!4v1748352187752!5m2!1sfr!2sfr" width="1535" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
</section>

<footer>
   <h3 class="text-uppercase font-weight-bold">Contact</h3>
 <p>
 37, quai de Grenelle, 75015 Paris, France <br>
 info@webDynamique.ece.fr <br>
 +33 01 02 03 04 05 <br>

 </p>
  <p>&copy; 2025 Sportify</p>
  
  
</footer>
</body>
</html>
