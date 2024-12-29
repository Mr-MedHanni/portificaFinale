<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $major = $_POST['major'] ?? '';
    $graduation_year = $_POST['graduation_year'] ?? '';
    $skills = $_POST['skills'] ?? [];

    try {
        // Mise à jour des informations générales
        $stmt = $pdo->prepare("UPDATE students SET major = :major, graduation_year = :graduation_year WHERE user_id = :user_id");
        $stmt->execute([
            'major' => $major,
            'graduation_year' => $graduation_year,
            'user_id' => $user_id,
        ]);

        // Gestion des compétences
        // Supprimer les anciennes compétences
        $stmt = $pdo->prepare("DELETE FROM student_skills WHERE student_id = :student_id");
        $stmt->execute(['student_id' => $user_id]);

        // Ajouter les nouvelles compétences
        foreach ($skills as $skill) {
            if (empty($skill)) continue;

            // Vérifier si la compétence existe
            $stmt = $pdo->prepare("SELECT id FROM skills WHERE name = :name");
            $stmt->execute(['name' => $skill]);
            $skillData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($skillData) {
                $skill_id = $skillData['id'];
            } else {
                // Insérer une nouvelle compétence
                $stmt = $pdo->prepare("INSERT INTO skills (name) VALUES (:name)");
                $stmt->execute(['name' => $skill]);
                $skill_id = $pdo->lastInsertId();
            }

            // Associer la compétence à l'étudiant
            $stmt = $pdo->prepare("INSERT INTO student_skills (student_id, skill_id) VALUES (:student_id, :skill_id)");
            $stmt->execute([
                'student_id' => $user_id,
                'skill_id' => $skill_id,
            ]);
        }

        header("Location: student_profile.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Erreur lors de la mise à jour du profil : " . htmlspecialchars($e->getMessage()));
    }
}
?>
