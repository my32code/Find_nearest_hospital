<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Hôpital</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Modifier un Hôpital</h2>
        <?php
        // Connexion à la base de données
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        
        // Récupération des données de l'hôpital
        $id = $_GET['id'];
        $stmt = $pdo->prepare("SELECT * FROM hopital WHERE id_hpt = ?");
        $stmt->execute([$id]);
        $hospital = $stmt->fetch();
        ?>
        <form action="edit_hospital_action.php" method="post">
            <input type="hidden" name="id" value="<?php echo $hospital['id_hpt']; ?>">
            <div class="form-group">
                <label for="hospitalName">Nom</label>
                <input type="text" class="form-control" id="hospitalName" name="hospitalName" value="<?php echo $hospital['nom']; ?>" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" value="<?php echo $hospital['latitude']; ?>" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" value="<?php echo $hospital['longitude']; ?>" required>
            </div>
            <div class="form-group">
                <label for="address">Adresse</label>
                <select name="address" id="address" class="form-control" required>
                <?php
                $stmt = $pdo->query("SELECT * FROM adress");
                while ($row = $stmt->fetch()) {
                    $selected = ($row['id_ad'] == $hospital['id_ad']) ? 'selected' : '';
                    echo "<option value='".$row['id_ad']."' $selected>".$row['ville'].", ".$row['commune'].", ".$row['arrondissement']."</option>";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Téléphone</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" value="<?php echo $hospital['numero']; ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Enregistrer</button>
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
