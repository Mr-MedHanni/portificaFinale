<?php
session_start();
require_once 'database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit();
}

$stmt = $pdo->query("SELECT id, name, email, user_type, created_at FROM users where user_type='recruiter' ");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer Les Recruteurs</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <main>
        <div style="display: flex; align-items: center; justify-content: space-between; position: relative;">
            <h2 class="text-center my-4" style="flex: 1; text-align: center; margin: 0;">Gérer Les Recruteurs</h2>
            <a href="admin_dashboard.php" class="btn btn-primary" style="position: absolute; left: 0;">Retour vers dashboard</a>
        </div>
        <table class="table ">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Type</th>
                    <th>Date de Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['id']) ?></td>
                        <td><?= htmlspecialchars($user['name']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['user_type']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td>
                        
                            <a href="delete_user.php?id=<?= $user['id'] ?>" onclick="return confirm('Confirmer la suppression ?')">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>

</html>

<?php include 'footer.php'; ?>