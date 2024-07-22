<?php
    session_start();

        if (isset($_POST['form1'])) {$id = $_GET['id'];
            $req = $pdo->prepare('SELECT * FROM etudiant WHERE id_etudiant = ?');
            $req->execute([$id]);
            // verifie si la requette a fonctionner
            // if($req){$etudiants = $req->fetchALL();}
            $etudiants = $req->fetchALL();
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                if(!empty($_POST['nonm'] AND $_POST["prenom"] AND $_POST["email"] AND $_POST["numero"])){
                    if (!$etudiants) {
                        // Rediriger l'utilisateur vers une autre page ou afficher un message d'erreur
                        echo "Étudiant non trouvé.";
                        exit();
                    }
                    $nom = $_POST['nom'];
                    $prenom = $_POST['prenom'];
                    $email = $_POST['email'];
                    $tel = $_POST['tel'];
                    
                    $update = $pdo->prepare('UPDATE etudiant SET nom = ?, prenom = ?, email = ?,tel = ? WHERE id_etudiant = ?');
                    $update->execute([$nom, $prenom, $email,$tel, $id]);
            
                    header('Location: liste.php');
                    exit();
                }
            }
        }
?>