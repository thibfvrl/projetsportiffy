<?php
// Connexion à la base de données
$host = "localhost";
$dbname = "faq";
$user = "root";
$pass = "";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Traitement de l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST" && !empty($_POST['question'])) {
    $question = trim($_POST['question']);

    $stmt = $pdo->prepare("INSERT INTO question (question) VALUES (:question)");
    $stmt->bindParam(':question', $question, PDO::PARAM_STR);

   if ($stmt->execute()) {
        echo "<script>alert(' Question ajoutée avec succès !');</script>";
    } else {
        echo "<script>alert(' Erreur lors de l\'ajout de la question.');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Nos Services – Salle de Sport</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #fff;
      margin: 0;
      padding: 0 20px;
      color: #111;
    }

    h1 {
      text-align: center;
      margin: 30px 0;
      color: #68d391;
    }

    .service-buttons {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      justify-content: center;
      margin-bottom: 20px;
      

    }

    .service-buttons button {
      background-color: #68d391;
      color: white;
      border: 2px solid black;
      padding: 12px 20px;
      font-size: 1rem;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .service-buttons button:hover {
      background-color: lightgray;

    }

    .content-section {
      display: none;
      border: 2px solid #68d391;
      padding: 20px;
      margin-bottom: 20px;
      border-radius: 12px;
      background-color: lightgray;
    }

    .calendar {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      margin-top: 20px;
    }

    .slot {
      background-color: #e0f7ea;
      border: 1px solid #68d391;
      border-radius: 8px;
      padding: 10px 15px;
      cursor: pointer;
      transition: background-color 0.2s;
    }

    .slot:hover {
      background-color: #c1f0d2;
    }

    .slot.booked {
      background-color: #ccc;
      cursor: not-allowed;
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
 .navbar {
      position: relative;
      height: 60px;
      background-color: black;
      padding: 0 40px;
      display: flex;
      align-items: center;
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
    #personnel .coach {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 20px;
  background-color: white;
  padding: 15px;
  border-radius: 10px;
  border: 1px solid #68d391;
}

.coach-photo {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 50%;
  border: 2px solid #68d391;
}
.faq-item {
    margin-bottom: 10px;
    border-bottom: 1px solid #68d391;
  }
  .faq-question {
    background-color: #68d391;
    color: white;
    border: none;
    width: 100%;
    text-align: left;
    padding: 12px 15px;
    font-size: 1rem;
    cursor: pointer;
    border-radius: 8px;
    transition: background-color 0.3s;
  }
  .faq-question:hover {
    background-color: #56b37a;
  }
  .faq-answer {
    display: none;
    padding: 10px 15px;
    background-color: #e0f7ea;
    border-radius: 0 0 8px 8px;
    color: #111;
  }
.ask-question {
  margin-top: 30px;
  padding: 15px;
  border-top: 2px solid #68d391;
}

.ask-question h3 {
  color: #68d391;
  margin-bottom: 15px;
}

#questionForm textarea {
  width: 100%;
  min-height: 80px;
  padding: 10px;
  font-size: 1rem;
  border-radius: 8px;
  border: 1px solid #68d391;
  resize: vertical;
  box-sizing: border-box;
}

#questionForm button {
  margin-top: 10px;
  background-color: #68d391;
  color: white;
  border: none;
  padding: 10px 20px;
  font-size: 1rem;
  border-radius: 8px;
  cursor: pointer;
  transition: background-color 0.3s;
}

#questionForm button:hover {
  background-color: #56b37a;
}
.nutrition-tips {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-top: 20px;
}

.tip {
  background-color: #e0f7ea;
  border: 2px solid #68d391;
  border-radius: 12px;
  padding: 15px 20px;
  cursor: pointer;
  transition: background-color 0.3s, box-shadow 0.3s;
}

.tip:hover {
  background-color: #c1f0d2;
  box-shadow: 0 0 10px #68d391;
}

.tip h3 {
  margin: 0;
  font-size: 1.2rem;
  color: #2f855a;
}

.tip-detail {
  margin-top: 10px;
  font-size: 1rem;
  color: #2c7a7b;
}

.nutrition-program {
  background-color: #d2f8d2;
  border: 2px solid #38a169;
  border-radius: 12px;
  padding: 20px;
  margin-top: 30px;
  color: #22543d;
}

.nutrition-program h2 {
  color: #276749;
  margin-top: 0;
}

.nutrition-program ul {
  margin-left: 20px;
  margin-bottom: 15px;
}

.nutrition-program li {
  margin-bottom: 8px;
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

  <h1>Nos Services</h1>

  <div class="service-buttons">
   
    <button onclick="showSection('horaire')">Horaire de la gym</button>
    <button onclick="showSection('regles')">Règles dans la salle</button>
    <button onclick="showSection('clients')">Nouveaux clients</button>
    <button onclick="showSection('nutrition')">Alimentation et nutrition</button>
  </div>

 


  <div id="horaire" class="content-section">
    <h2>Horaires de la gym</h2>
    <p>Du lundi au vendredi : 6h - 22h<br>Week-end : 8h - 20h</p>
  </div>

  <div id="regles" class="content-section">
    <h2>Règles sur l’utilisation des machines</h2>
    <p>
      Chez Sportify nous voulons que tout le monde puisse faire du sport en toute sécurité dans un environnement 

      agréable. Quiconque entre dans le club s’engage à respecter les règles suivantes :



      <p> <em> <u> ENSEMBLE, NOUS GARDONS LE CLUB PROPRE ET SAIN </u> </em><br>
      Le port de chaussures de sport (propres) et de vêtements de sport appropriés est obligatoire.
      Placez votre serviette sur l’appareil que vous utilisez puis, pour l’hygiène de tous, nettoyez l’appareil avec les produits désinfectants mis à votre disposition.
      La nourriture et les récipients non refermables ne sont pas autorisés dans les espaces d’entraînement.
      Il est interdit de fumer, de faire usage et/ou de distribuer des substances illicites dans le club.
      Les animaux ne sont pas admis au club, hormis les chiens d’assistance. </p>
      <p>
     <em> <u>  NOUS RESPECTONS LA VIE PRIVÉE ET LA TRANQUILLITÉ DES AUTRES PERSONNES </u></em><br>

      L’utilisation d’appareils mobiles fait partie intégrante de notre société. Dans les espaces d’entraînement, il n’est pas permis de téléphoner et de prendre des photos ou des films portant atteinte à la vie privée des personnes présentes.
      Ne laissez pas tomber le matériel bruyamment, limitez les bruits de toutes sortes ainsi que le niveau sonore de votre musique.
      La violence verbale et/ou physique n’est pas tolérée dans le club.
      Les gestes, harcèlement, et/ou relations intimes/sexuels ne sont pas tolérés dans le club. 
      Laissez les appareils disponibles pour les autres si vous ne les utilisez pas de manière active. </p>
      <p>  <em> <u> NOUS VOULONS UN CLUB SÛR POUR CHACUN </u> </em><br>

      Il est obligatoire de présenter l’original de la carte Sportify valide pour entrer dans le club. 
      Les enfants de moins de 16 ans ne sont pas admis dans le club. 
      Les instructions du personnel de Sportify doivent être respectées et suivies. Sportify est habilité, s’il a des motifs raisonnables de le faire, à refuser temporairement ou définitivement l’accès au(x) club(s) aux personnes qui ne respecteraient pas les conditions générales et/ou le règlement intérieur. 
      Assurez-vous de savoir comment utiliser les appareils et faire les exercices. Respectez vos limites et veillez à connaître ce dont vous êtes capable, éventuellement avec les conseils d’un spécialiste (médical). Vous êtes responsable de votre propre bien-être.
      N’utilisez un appareil que pour l’usage auquel il est destiné et, après l’exercice, éteignez l’appareil ou remettez le matériel à l’endroit prévu à cet effet.
      Les effets personnels non nécessaires à la pratique du sport tels que manteaux, sacs, casques etcetera, ne peuvent être emmenés dans les espaces d’entraînement, ils doivent être rangés dans les casiers. Nous vous conseillons de ne pas emporter d’objets de valeur et d’utiliser les casiers pour ranger vos affaires. Les casiers sont vidés tous les jours. Pour la responsabilité en cas de dommage ou de vol, nous vous renvoyons aux conditions générales en vigueur de Sportify. 
      Après la douche, séchez-vous dans la zone humide du vestiaire pour éviter les chutes et les salissures inutiles dans les vestiaires. Pour des raisons d’hygiène, il n’est pas autorisé de se raser dans les vestiaires.
      Les activités de vente et/ou de promotion ainsi que le coaching personnel ne sont pas autorisés sans l’accord préalable et écrit de Sportify. </p>
    </p>

   
  </div>

  <div id="clients" class="content-section">
  <h2>Nouveaux clients</h2>
  <p>Une séance d’accueil gratuite est offerte ! Profitez d’un tour des installations et d’un bilan forme personnalisé. <br> Vous avez des doutes ? Des questions ? Posez 
  les ci-dessous </p>

  <h3>FAQ - Questions fréquentes</h3>
  <div class="faq-item">
    <button class="faq-question">Comment puis-je m’inscrire à la salle ?</button>
    <div class="faq-answer">
      <p>Vous pouvez vous inscrire directement à l’accueil de la salle ou via notre site web en créant un compte personnel.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Quels moyens de paiement acceptez-vous ?</button>
    <div class="faq-answer">
      <p>Nous acceptons les paiements par carte bancaire, espèces, chèques, ainsi que les virements bancaires pour les abonnements.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Y a-t-il des tarifs réduits pour les étudiants ou les seniors ?</button>
    <div class="faq-answer">
      <p>Oui, nous proposons des tarifs préférentiels pour les étudiants, les seniors, et certaines professions. Contactez-nous pour plus d’informations.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Puis-je suspendre mon abonnement temporairement ?</button>
    <div class="faq-answer">
      <p>Oui, il est possible de suspendre votre abonnement pour une durée minimale d’un mois, sous réserve de nous prévenir à l’avance.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Quelles mesures sanitaires sont en place dans la salle ?</button>
    <div class="faq-answer">
      <p>Nous respectons les protocoles sanitaires recommandés : nettoyage régulier des équipements, gel hydroalcoolique à disposition, et respect des distances de sécurité.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Est-ce que la salle est accessible aux personnes à mobilité réduite ?</button>
    <div class="faq-answer">
      <p>Oui, notre salle est équipée pour accueillir les personnes à mobilité réduite, avec des accès adaptés et des équipements spécifiques.</p>
    </div>
  </div>

  <div class="faq-item">
    <button class="faq-question">Puis-je venir avec un ami sans abonnement ?</button>
    <div class="faq-answer">
      <p>Les invités non abonnés peuvent bénéficier d’un pass invité pour une séance, disponible à l’accueil.</p>
    </div>
  </div>
  <div class="ask-question">
  <h3>Posez vos questions</h3>
  <form method="POST" action="">
        <label for="question">Votre question :</label><br>
        <textarea name="question" id="question" rows="4" cols="50" required></textarea><br><br>
        <button type="submit">Envoyer</button>
    </form>
  <p id="formMessage" style="display:none; color:green; margin-top:10px;">Merci pour votre question, nous y répondrons rapidement !</p>
</div>

</div>




<!-- Section Nutrition maintenant correctement intégrée -->
<div id="nutrition" class="content-section">
  <h2>Alimentation et nutrition</h2>
  <p>Découvrez nos conseils nutritionnels pour optimiser vos performances sportives et atteindre vos objectifs.</p>
  
  <div class="nutrition-tips">
    <div class="tip" onclick="toggleTip(this)">
      <h3>Hydratation optimale</h3>
      <p class="tip-detail" style="display:none;">
        Buvez au moins 1,5 à 2 litres d'eau par jour, et augmentez cette quantité lors de vos séances de sport pour rester performant et éviter la déshydratation.
      </p>
    </div>

    <div class="tip" onclick="toggleTip(this)">
      <h3>Repas avant l'entraînement</h3>
      <p class="tip-detail" style="display:none;">
        Privilégiez un repas riche en glucides complexes et modéré en protéines environ 2 à 3 heures avant votre séance pour avoir de l'énergie durable.
      </p>
    </div>

    <div class="tip" onclick="toggleTip(this)">
      <h3>Collation post-entraînement</h3>
      <p class="tip-detail" style="display:none;">
        Après l'effort, consommez une collation avec un bon équilibre protéines-glucides pour favoriser la récupération musculaire.
      </p>
    </div>

    <div class="tip" onclick="toggleTip(this)">
      <h3>Varier les aliments</h3>
      <p class="tip-detail" style="display:none;">
        Mangez une grande variété d'aliments naturels : fruits, légumes, céréales complètes, protéines maigres, et bonnes graisses pour un équilibre optimal.
      </p>
    </div>

    <div class="tip" onclick="toggleTip(this)">
      <h3>Limiter les sucres rapides</h3>
      <p class="tip-detail" style="display:none;">
        Évitez les sucres rapides (bonbons, sodas) en dehors des phases d'effort intense, ils peuvent provoquer des pics d'énergie suivis de coups de fatigue.
      </p>
    </div>

    <div class="tip" onclick="toggleTip(this)">
      <h3>Écouter son corps</h3>
      <p class="tip-detail" style="display:none;">
        Chaque corps réagit différemment, apprenez à reconnaître vos besoins et évitez les régimes drastiques non adaptés à votre pratique.
      </p>
    </div>
  </div>

  <div class="nutrition-program">
    <h2>Conseils perte de poids</h2>
    <p>Conseils et programme pour perdre du poids sainement tout en gardant de l'énergie pour le sport.</p>
    <ul>
      <li>Réduisez légèrement les apports caloriques, privilégiez les aliments riches en fibres (légumes, fruits, céréales complètes).</li>
      <li>Favorisez les protéines maigres pour conserver la masse musculaire (poulet, poisson, tofu).</li>
      <li>Évitez les aliments transformés, sucres ajoutés et boissons sucrées.</li>
      <li>Privilégiez 5 petits repas par jour pour maintenir un bon métabolisme.</li>
    </ul>
    <h3>Programme type :</h3>
    <ul>
      <li><strong>Petit-déjeuner :</strong> Flocons d'avoine, lait d'amande, fruits rouges</li>
      <li><strong>Déjeuner :</strong> Salade composée avec quinoa, légumes grillés, poulet grillé</li>
      <li><strong>Dîner :</strong> Poisson vapeur, légumes verts, patate douce</li>
      <li><strong>Collations :</strong> Yaourt nature, amandes, pomme</li>
    </ul>
  </div>

  <div class="nutrition-program">
    <h2>Conseils prise de poids / Muscle</h2>
    <p>Conseils et programme pour prendre du muscle avec une alimentation adaptée.</p>
    <ul>
      <li>Augmentez les apports caloriques avec des aliments riches en nutriments (glucides complexes, protéines, bonnes graisses).</li>
      <li>Consommez suffisamment de protéines (environ 1.6 à 2g/kg de poids corporel).</li>
      <li>Privilégiez des repas complets et équilibrés pour une récupération optimale.</li>
      <li>Hydratez-vous bien et ne négligez pas les glucides pour l'énergie.</li>
    </ul>
    <h3>Programme type :</h3>
    <ul>
      <li><strong>Petit-déjeuner :</strong> Omelette aux légumes, pain complet, avocat</li>
      <li><strong>Déjeuner :</strong> Riz complet, steak maigre, brocoli vapeur</li>
      <li><strong>Dîner :</strong> Poulet grillé, patate douce, salade verte</li>
      <li><strong>Collations :</strong> Smoothie protéiné, noix, banane</li>
    </ul>
  </div>
</div>

  <script>
  function showSection(id) {
    // Masquer toutes les sections
    const sections = document.querySelectorAll('.content-section');
    sections.forEach(section => section.style.display = 'none');

    // Afficher uniquement la section sélectionnée
    const selected = document.getElementById(id);
    if (selected) {
      selected.style.display = 'block';
    }
  }

  // Pour les FAQ (affichage des réponses)
  const faqButtons = document.querySelectorAll('.faq-question');
  faqButtons.forEach(btn => {
    btn.addEventListener('click', () => {
      const answer = btn.nextElementSibling;
      if (answer.style.display === 'block') {
        answer.style.display = 'none';
      } else {
        answer.style.display = 'block';
      }
    });
  });

  // Pour les tips nutrition (clic pour afficher le détail)
  function toggleTip(tipElement) {
    const detail = tipElement.querySelector('.tip-detail');
    detail.style.display = detail.style.display === 'block' ? 'none' : 'block';
  }

  // Message après envoi du formulaire
  const form = document.getElementById('questionForm');
  form.addEventListener('submit', function (e) {
    e.preventDefault();
    document.getElementById('formMessage').style.display = 'block';
    form.reset();
  });
</script>

 </main>
</body>
</html>
