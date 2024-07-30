<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de Connexion</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('DOC2.jpg');
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px 0px rgba(0, 0, 0, 0.1);
            width: 400px;
        }
        .login-container h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #007bff;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-primary {
            width: 100%;
        }
        .alert {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form action="authentificate_admin.php" method="post">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
            <?php
            if (isset($_GET['error'])) {
                echo '<div class="alert alert-danger" role="alert">Identifiant ou mot de passe incorrect.</div>';
            }
            ?>
        </form>
    </div>
</body>
</html>
