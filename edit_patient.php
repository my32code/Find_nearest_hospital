<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Patient</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Modifier un Patient</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données du patient
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM patient WHERE id_pat = ?");
        $stmt->execute([$id]);
        $patient = $stmt->fetch();
        ?>
        <form action="edit_patient_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $patient['id_pat']; ?>">
            <div class="form-group">
                <label for="patientName">Nom</label>
                <input type="text" class="form-control" id="patientName" name="patientName" value="<?php echo $patient['nom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="patientFirstName">Prénom</label>
                <input type="text" class="form-control" id="patientFirstName" name="patientFirstName" value="<?php echo $patient['prenom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="sexe">Sexe</label>
                <input type="text" class="form-control" id="sexe" name="sexe" value="<?php echo $patient['sexe']; ?>" required>
            </div>
            
            <div class="form-group">
                <label for="phoneNumber">Téléphone</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $patient['numero']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo $patient['email']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="dashbord.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
