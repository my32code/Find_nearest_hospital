<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demander un rendez-vous</title>
    <style>
        body {
            font-family: "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", Verdana, sans-serif;
            color: #555;
            max-width: 600px;
            margin: auto;
        }
        .form-section {
            margin: 0;
            font-size: 14px;
        }
        .form-line {
            margin-bottom: 20px;
            position: relative;
        }
        .form-label {
            display: block;
            margin-bottom: 5px;
        }
        .form-input {
            position: relative;
        }
        .form-input input, .form-input select {
            width: calc(100% - 10px);
            padding: 5px;
            font-size: 14px;
        }
        .form-input-wide {
            width: 100%;
        }
        .form-header-group {
            margin-bottom: 30px;
        }
        .header-text {
            font-size: 24px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Demander un rendez-vous</h1>
    <form class="form-section" method="post" action="rendezvous_handler.php">
        <div class="form-line form-header-group">
            <div class="header-text">Demander un rendez-vous</div>
        </div>
        
        <div class="form-line">
            <label class="form-label" for="id_pat">ID Patient</label>
            <div class="form-input">
                <input type="text" id="id_pat" name="id_pat" class="form-textbox" required>
            </div>
        </div>
        
        <div class="form-line">
            <label class="form-label" for="id_doc">ID Docteur</label>
            <div class="form-input">
                <input type="text" id="id_doc" name="id_doc" class="form-textbox" required>
            </div>
        </div>

        <div class="form-line">
            <label class="form-label" for="disponibilite_doc">Disponibilités du Docteur</label>
            <div class="form-input">
                <select id="disponibilite_doc" name="disponibilite_doc" class="form-textbox" required>
                    <!-- Les options seront ajoutées par le script PHP -->
                </select>
            </div>
        </div>
        
        <div class="form-line">
            <label class="form-label" for="description">Description</label>
            <div class="form-input">
                <textarea id="description" name="description" class="form-textbox" rows="4" placeholder="Description du rendez-vous" required></textarea>
            </div>
        </div>
        
        <div class="form-line">
            <button type="submit" class="form-submit">Soumettre</button>
        </div>
    </form>

    <?php
    // Connexion à la base de données
    try {
        $pdo = new PDO('mysql:host=localhost;dbname=soutenance1;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Récupération des disponibilités des docteurs
        $stmt = $pdo->query("SELECT id_doc, disponibility FROM docteur");
        $disponibilites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Génération des options pour le select
        echo '<script>';
        echo 'let disponibiliteSelect = document.getElementById("disponibilite_doc");';
        foreach ($disponibilites as $disponibilite) {
            $id_doc = $disponibilite['id_doc'];
            $dispo = htmlspecialchars($disponibilite['disponibilite'], ENT_QUOTES, 'UTF-8');
            echo "let option = document.createElement('option');";
            echo "option.value = '{$id_doc} - {$dispo}';";
            echo "option.text = 'Docteur {$id_doc}: {$dispo}';";
            echo "disponibiliteSelect.appendChild(option);";
        }
        echo '</script>';
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    ?>
</body>
</html>
