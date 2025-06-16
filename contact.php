<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Variables de connexion à la base de données pour XAMPP
    $serveur = "localhost"; // Adresse du serveur MySQL, généralement localhost pour XAMPP
    $utilisateur = 'root'; // Nom d'utilisateur MySQL
    $mot_de_passe = ""; // Mot de passe MySQL
    $base_de_donnees = "polycontact";

    // Créez une connexion à la base de données
    $conn = new mysqli($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Récupérez les données du formulaire
    $email = $_POST['email'];
    $messager = $_POST['messager'];

    // Vérifiez si les champs ne sont pas vides
    if (empty($email) || empty($messager)) {
        echo '<script>alert("Tous les champs sont obligatoires."); window.location.href = "index.html";</script>';
    } else {
        // Préparez et exécutez la requête SQL pour insérer les données dans la base de données
        $requete = $conn->prepare("INSERT INTO contact_table (email, messager) VALUES (?, ?)");
        $requete->bind_param("ss", $email, $messager);

        if ($requete->execute()) {
            echo '<script>alert("Your message has been sent successfully. Thank you!");window.location.href = "index.html";</script>';
        } else {
            echo '<script>alert("Error sending the message: ' . $requete->error . '");</script>';
        }

        // Fermez la requête
        $requete->close();
    }

    // Fermez la connexion à la base de données
    $conn->close();
}
?>
