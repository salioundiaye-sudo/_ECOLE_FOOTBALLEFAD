<?php
$servername = "localhost";
$username = "root"; // Par défaut dans XAMPP
$password = ""; // Pas de mot de passe par défaut
$dbname = "ecole_football"; // Mettre le bon nom de la base

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Échec de la connexion : " . $conn->connect_error);
}

// Vérifier si les champs existent dans $_POST
if (isset($_POST['telephone'], $_POST['nom'], $_POST['age'], $_POST['categorie'])) {
    $telephone = $_POST['telephone'];
    $nom = $_POST['nom'];
    $age = $_POST['age'];
    $categorie = $_POST['categorie'];

    // Vérifier si le téléphone existe déjà
    $check = $conn->prepare("SELECT telephone FROM inscription WHERE telephone = ?");
    $check->bind_param("s", $telephone);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "Erreur : Le numéro de téléphone existe déjà.";
    } else {
        // Utilisation d'une requête préparée pour éviter les injections SQL
        $stmt = $conn->prepare("INSERT INTO inscription (telephone, nom, age, categorie) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $telephone, $nom, $age, $categorie);

        if ($stmt->execute()) {
            echo "Inscription réussie !";
        } else {
            echo "Erreur lors de l'inscription : " . $stmt->error;
        }

        $stmt->close();
    }

    $check->close();
} else {
    echo "Erreur : Veuillez remplir tous les champs.";
}

// Fermer la connexion
$conn->close();
?>