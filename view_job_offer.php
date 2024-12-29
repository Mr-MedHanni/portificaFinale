<?php
// view_job_offer.php
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

try {
    $stmt = $pdo->prepare("SELECT * FROM job_offers WHERE id = :job_id AND recruiter_id = :recruiter_id");
    $stmt->execute(['job_id' => $job_id, 'recruiter_id' => $_SESSION['user_id']]);
    $job_offer = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$job_offer) {
        header("Location: dashboard.php");
        exit();
    }
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération de l'offre d'emploi : " . $e->getMessage();
}

$pageTitle = "TalentMatcher UPF - Détails de l'offre d'emploi";
include 'header.php';
?>

<main>
    <h2>Détails de l'offre d'emploi</h2>
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php else: ?>
        <h3><?php echo htmlspecialchars($job_offer['title']); ?></h3>
        <p><strong>Date de publication :</strong> <?php echo date('d/m/Y', strtotime($job_offer['posted_date'])); ?></p>
        
        <h4>Description :</h4>
        <p><?php echo nl2br(htmlspecialchars($job_offer['description'])); ?></p>
        
        <h4>Prérequis :</h4>
        <p><?php echo nl2br(htmlspecialchars($job_offer['requirements'])); ?></p>
        
        <a href="edit_job_offer.php?id=<?php echo $job_offer['id']; ?>">Modifier cette offre</a>
        <a href="delete_job_offer.php?id=<?php echo $job_offer['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?');">Supprimer cette offre</a>
    <?php endif; ?>
    
    <p><a href="dashboard.php">Retour au tableau de bord</a></p>
</main>

<?php include 'footer.php'; ?>