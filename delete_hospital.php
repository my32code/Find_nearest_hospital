<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Hôpital</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Supprimer un Hôpital</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données de l'hôpital
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM hopital WHERE id_hpt = ?");
        $stmt->execute([$id]);
        $hospital = $stmt->fetch();
        ?>
        <form action="delete_hospital_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $hospital['id_hpt']; ?>">
            <p>Êtes-vous sûr de vouloir supprimer l'hôpital suivant ?</p>
            <p><strong>Nom :</strong> <?php echo $hospital['nom']; ?></p>
            <p><strong>Coordonnées :</strong> <?php echo $hospital['latitude'] . ", " . $hospital['longitude']; ?></p>
            <button type="submit" class="btn btn-danger">Supprimer</button>
            <a href="dashbord.php" class="btn btn-secondary">Annuler</a>
        </form>
    </div>
</body>
</html>
