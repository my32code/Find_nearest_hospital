<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Médecin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Supprimer un Médecin</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données du médecin
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM docteur WHERE id_doc = ?");
        $stmt->execute([$id]);
        $doctor = $stmt->fetch();
        ?>
        <form action="delete_doctor_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $doctor['id_doc']; ?>">
            <p>Êtes-vous sûr de vouloir supprimer le médecin suivant ?</p>
            <p><strong>Nom :</strong> <?php echo $doctor['nom']; ?></p>
            <p><strong>Prénom :</strong> <?php echo $doctor['prenom']; ?></p>
            <p><strong>Hôpital :</strong> <?php echo $doctor['id_hpt']; ?></p>
            <p><strong>Spécialité :</strong> <?php echo $doctor['id_sp']; ?></p>
            <p><strong>Téléphone :</strong> <?php echo $doctor['numero']; ?></p>
            <button type="submit" class="btn btn-danger">Supprimer</button>
            <a href="dashbord.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
