<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Médecin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Modifier un Médecin</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données du médecin
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM docteur WHERE id_doc = ?");
        $stmt->execute([$id]);
        $doctor = $stmt->fetch();
        ?>
        <form action="edit_doctor_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $doctor['id_doc']; ?>">
            <div class="form-group">
                <label for="doctorName">Nom</label>
                <input type="text" class="form-control" id="doctorName" name="doctorName" value="<?php echo $doctor['nom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="doctorFirstName">Prénom</label>
                <input type="text" class="form-control" id="doctorFirstName" name="doctorFirstName" value="<?php echo $doctor['prenom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="hospital">Hôpital</label>
                <select name="hospital" id="hospital" class="form-control" required>
                <?php
                $stmt = $pdo->query("SELECT * FROM hopital");
                while ($row = $stmt->fetch()) {
                    $selected = ($row['id_hpt'] == $doctor['id_hpt']) ? 'selected' : '';
                    echo "<option value='".$row['id_hpt']."' $selected>".$row['nom']."</option>";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="speciality">Spécialité</label>
                <select name="speciality" id="speciality" class="form-control" required>
                <?php
                $stmt = $pdo->query("SELECT * FROM speciality");
                while ($row = $stmt->fetch()) {
                    $selected = ($row['id_sp'] == $doctor['id_sp']) ? 'selected' : '';
                    echo "<option value='".$row['id_sp']."' $selected>".$row['libelle']."</option>";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Téléphone</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $doctor['numero']; ?>" required>
            </div>
            <div class="form-group">
                <label for="disponibility">Disponibilité</label>
                <input type="text" class="form-control" id="disponibility" name="disponibility" value="<?php echo $doctor['disponibility']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="dashbord.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
