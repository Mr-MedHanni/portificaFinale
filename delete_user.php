<?php
session_start();
require_once 'database.php'; // Inclure la connexion à la base de données

// Vérifiez si l'utilisateur est connecté et a les droits d'administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    die("Accès non autorisé.");
}

// Vérifiez que l'ID de l'utilisateur à supprimer est passé en paramètre
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID utilisateur invalide.");
}

$user_id = (int) $_GET['id'];

try {
    // Vérifiez si l'utilisateur existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $stmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        die("Utilisateur introuvable.");
    }

    // Supprimez l'utilisateur de la base de données
    $deleteStmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
    $deleteStmt->bindParam(':id', $user_id, PDO::PARAM_INT);
    $deleteStmt->execute();

    // Redirigez avec un message de confirmation
    header("Location: user_list.php?success=Utilisateur supprimé avec succès.");
    exit;
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur lors de la suppression de l'utilisateur : " . htmlspecialchars($e->getMessage()) . "</p>";
}
