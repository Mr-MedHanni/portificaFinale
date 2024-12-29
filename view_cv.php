<?php
session_start();
require_once 'database.php'; // Inclure la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    die("Accès non autorisé.");
}

$student_id = $_SESSION['user_id'];

try {
    // Récupérer le chemin du fichier CV pour l'étudiant connecté
    $stmt = $pdo->prepare("SELECT file_path FROM cvs WHERE student_id = :student_id ORDER BY upload_date DESC LIMIT 1");
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();

    $cv = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cv && file_exists($cv['file_path'])) {
        // Afficher le CV (fichier PDF)
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($cv['file_path']) . '"');
        readfile($cv['file_path']);
        exit;
    } else {
        echo "<p style='color: red;'>CV introuvable pour cet utilisateur.</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>Erreur lors de la récupération du CV : " . htmlspecialchars($e->getMessage()) . "</p>";
}
?>


