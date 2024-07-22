<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Hôpital</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter un Hôpital</h2>
        <form action="add_hospital_action.php" method="post">
            <div class="form-group">
                <label for="hospitalName">Nom</label>
                <input type="text" class="form-control" id="hospitalName" name="hospitalName" required>
            </div>
            <div class="form-group">
                <label for="latitude">Latitude</label>
                <input type="text" class="form-control" id="latitude" name="latitude" required>
            </div>
            <div class="form-group">
                <label for="longitude">Longitude</label>
                <input type="text" class="form-control" id="longitude" name="longitude" required>
            </div>
            <div class="form-group">
                <label for="address">Adresse</label>
                <select name="address" id="address" class="form-control" required>
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
                $stmt = $pdo->query("SELECT * FROM adress");
                while ($row = $stmt->fetch()) {
                    echo "<option value='".$row['id_ad']."'>".$row['ville'].", ".$row['commune'].", ".$row['arrondissement']."</option>";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Téléphone</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="index.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>
</body>
</html>
