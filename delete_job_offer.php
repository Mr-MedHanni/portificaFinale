<?php
// delete_job_offer.php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$job_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($job_id === 0) {
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $stmt = $pdo->prepare("DELETE FROM job_offers WHERE id = :job_id AND recruiter_id = :recruiter_id");
        $stmt->execute([
            'job_id' => $job_id,
            'recruiter_id' => $_SESSION['user_id']
        ]);
        
        if ($stmt->rowCount() > 0) {
            $success = "L'offre d'emploi a été supprimée avec succès.";
        } else {
            $error = "Aucune offre d'emploi n'a été trouvée ou vous n'avez pas les droits pour la supprimer.";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la suppression de l'offre d'emploi : " . $e->getMessage();
    }
}

$pageTitle = "TalentMatcher UPF - Supprimer une offre d'emploi";
include 'header.php';
?>

<main>
    <h2>Supprimer une offre d'emploi</h2>
    
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p class="success"><?php echo $success; ?></p>
        <p><a href="dashboard.php">Retour au tableau de bord</a></p>
    <?php else: ?>
        <p>Êtes-vous sûr de vouloir supprimer cette offre d'emploi ?</p>
        <form action="delete_job_offer.php?id=<?php echo $job_id; ?>" method="POST">
            <input type="submit" value="Oui, supprimer cette offre">
        </form>
        <p><a href="view_job_offer.php?id=<?php echo $job_id; ?>">Non, retourner aux détails de l'offre</a></p>
    <?php endif; ?>
</main>

<?php include 'footer.php'; ?>