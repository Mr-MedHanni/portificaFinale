<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

$pageTitle = "Tableau de Bord Admin - TalentMatcher UPF";
include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="style.css">
</head>

<body style=" display: flex; flex-direction: column; min-height: 100vh;">
    <main class="text-center my-5">
        <h2>Bienvenue, Mr Elouadi</h2>
        <p>Que souhaitez-vous faire ?</p>
        <!-- <ul>
            <li><a href="manage_students.php">Gérer les Etudients</a></li>
            <li><a href="manage_recruters.php">Gérer les Recruteurs</a></li>
            <li><a href="view_reports.php">Voir les rapports</a></li>
            <li><a href="logout.php">Déconnexion</a></li>
        </ul> -->

        <ul class="nav justify-content-center">
            <li class="nav-item">
                <a class=" mx-4 btn btn-primary" href="manage_students.php">Gérer les Etudients</a>
            </li>
            <li class="nav-item">
                <a class=" mx-4 btn btn-info" href="manage_recruters.php">Gérer les Recruteurs</a>
            </li>
            <li class="nav-item">
                <a class=" mx-4 btn btn-warning" href="view_reports.php">Voir les rapports</a>
            </li>         
            <li class="nav-item">
                <a class=" mx-4 btn btn-danger" href="logout.php">Déconnexion</a>
            </li>     
        </ul>
    </main>
</body>

</html>

<?php include 'footer.php'; ?>