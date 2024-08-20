<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Médecin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Ajouter un Médecin</h2>
        <form action="add_doctor_action.php" method="post">
            <div class="form-group">
                <label for="doctorName">Nom</label>
                <input type="text" class="form-control" id="doctorName" name="doctorName" required>
            </div>
            <div class="form-group">
                <label for="doctorFirstName">Prénom</label>
                <input type="text" class="form-control" id="doctorFirstName" name="doctorFirstName" required>
            </div>
            <div class="form-group">
                <label for="hospital">Hôpital</label>
                <select name="hospital" id="hospital" class="form-control" required>
                <?php
                $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
                $stmt = $pdo->query("SELECT * FROM hopital");
                while ($row = $stmt->fetch()) {
                    echo "<option value='".$row['id_hpt']."'>".$row['nom']."</option>";
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
                    echo "<option value='".$row['id_sp']."'>".$row['libelle']."</option>";
                }
                ?>
                </select>
            </div>
            <div class="form-group">
                <label for="phoneNumber">Téléphone</label>
                <input type="text" class="form-control" id="phoneNumber" name="phoneNumber" required>
            </div>
            <div class="form-group">
                <label for="disponibility">Disponibilité</label>
                <div id="disponibility-container">
                    <div class="input-group mb-3">
                        <input type="datetime-local" class="form-control" name="start_time[]" required>
                        <input type="datetime-local" class="form-control" name="end_time[]" required>
                        <div class="input-group-append">
                            <button class="btn btn-danger remove-time-slot" type="button">Retirer</button>
                        </div>
                    </div>
                </div>
                <button class="btn btn-success" id="add-time-slot" type="button">Ajouter une plage horaire</button>
            </div>
            <button type="submit" class="btn btn-primary">Ajouter</button>
            <a href="dashbord.php" class="btn btn-secondary">Retour</a>
        </form>
    </div>

    <script>
        document.getElementById('add-time-slot').addEventListener('click', function() {
            var container = document.getElementById('disponibility-container');
            var timeSlot = document.createElement('div');
            timeSlot.className = 'input-group mb-3';
            timeSlot.innerHTML = `
                <input type="datetime-local" class="form-control" name="start_time[]" required>
                <input type="datetime-local" class="form-control" name="end_time[]" required>
                <div class="input-group-append">
                    <button class="btn btn-danger remove-time-slot" type="button">Retirer</button>
                </div>
            `;
            container.appendChild(timeSlot);
        });

        document.getElementById('disponibility-container').addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-time-slot')) {
                e.target.closest('.input-group').remove();
            }
        });
    </script>
</body>
</html>
