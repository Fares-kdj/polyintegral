<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Variables de connexion à la base de données pour XAMPP
$serveur = "localhost"; // Adresse du serveur MySQL, généralement localhost pour XAMPP
$utilisateur = 'root'; // Nom d'utilisateur MySQL
$mot_de_passe = ""; // Mot de passe MySQL
$base_de_donnees = "polyintegral"; // Nom de la base de données à laquelle vous souhaitez vous connecter

    // Créez une connexion à la base de données
    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérez les données du formulaire
    $nom = $_POST['firstname'];
    $prenom = $_POST['lastname'];
    $specialite = $_POST['specialization'];
    $email = $_POST['email'];
    $numero = $_POST['phone'];

    // Vérifiez si tous les champs sont remplis
    if (empty($nom) || empty($prenom) || empty($specialite) || empty($email) || empty($numero)) {
        echo '<script>alert("All fields are mandatory.");</script>';
    } else {
        // Vérifiez d'abord si l'e-mail existe déjà dans la base de données
        $verif_email = $conn->prepare("SELECT email FROM ma_table WHERE email = ?");
        $verif_email->bind_param("s", $email);
        $verif_email->execute();
        $verif_email->store_result();
        $count = $verif_email->num_rows;

        if ($count > 0) {
            echo '<script>alert("The email already exists. You are already registered."); window.location.href = "form.html";</script>';
        } else {
            // L'e-mail n'existe pas encore, nous pouvons l'insérer dans la base de données
            $requete = $conn->prepare("INSERT INTO ma_table (nom, prenom, specialite, email, numero) VALUES (?, ?, ?, ?, ?)");
            $requete->bind_param("sssss", $nom, $prenom, $specialite, $email, $numero);
        
            if ($requete->execute()) {
                echo '<script>alert("Registration successful. Thank you!"); window.location.href = "form.html";</script>';
            } else {
                echo '<script>alert("Error during registration." : ' . $requete->error . '");</script>';
            }
        
            $requete->close();
        }

        $verif_email->close();
    }

    // Fermez la connexion à la base de données
    $conn->close();
}
?>
