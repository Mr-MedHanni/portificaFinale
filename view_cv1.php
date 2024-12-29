<?php
require_once 'database.php'; // Include database connection

// Check if the student ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID étudiant manquant.");
}

$student_id = (int) $_GET['id'];

try {
    // Fetch the CV file path for the student
    $stmt = $pdo->prepare("SELECT file_path FROM cvs WHERE student_id = :student_id ORDER BY upload_date DESC LIMIT 1 ");
    $stmt->bindParam(':student_id', $student_id, PDO::PARAM_INT);
    $stmt->execute();

    $cv = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($cv && file_exists($cv['file_path'])) {
        // Display the CV (PDF file)
        header('Content-Type: application/pdf');
        header('Content-Disposition: inline; filename="' . basename($cv['file_path']) . '"');
        readfile($cv['file_path']);
        exit;
    } else {
        echo "CV introuvable pour cet étudiant.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
