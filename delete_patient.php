<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Patient</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Supprimer un Patient</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données du patient
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE id_pat = ?");
        $stmt->execute([$id]);
        $patient = $stmt->fetch();
        ?>
        <form action="delete_patient_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $patient['id_pat']; ?>">
            <p>Êtes-vous sûr de vouloir supprimer le patient suivant ?</p>
            <p><strong>Nom :</strong> <?php echo $patient['nom']; ?></p>
            <p><strong>Prénom :</strong> <?php echo $patient['prenom']; ?></p>
            <p><strong>Sexe :</strong> <?php echo $patient['sexe']; ?></p>
            <p><strong>Hôpital :</strong> <?php echo $patient['id_hpt']; ?></p>
            <p><strong>Téléphone :</strong> <?php echo $patient['numero']; ?></p>
            <p><strong>Email :</strong> <?php echo $patient['email']; ?></p>
            <button type="submit" class="btn btn-danger">Supprimer</button>
            <a href="dashbord.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
