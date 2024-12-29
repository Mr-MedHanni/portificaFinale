<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'recruiter') {
    header("Location: login.php");
    exit();
}

$itemsPerPage = 3;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$searchTerms = array_filter(explode(' ', $search));

require_once 'database.php';

try {
    $query = "SELECT u.name AS student_name, u.email, 
                     GROUP_CONCAT(DISTINCT sk.name SEPARATOR ', ') AS skills, 
                     cv.file_path AS cv
              FROM users u
              LEFT JOIN students s ON u.id = s.user_id
              LEFT JOIN student_skills ss ON s.user_id = ss.student_id
              LEFT JOIN skills sk ON ss.skill_id = sk.id
              LEFT JOIN cvs cv ON s.user_id = cv.student_id
              WHERE u.user_type = 'student'";

    if (!empty($searchTerms)) {
        $searchConditions = [];
        foreach ($searchTerms as $index => $term) {
            $searchConditions[] = "sk.name LIKE :search{$index}";
        }
        $query .= " AND (" . implode(' OR ', $searchConditions) . ")";
    }

    $query .= " GROUP BY u.id LIMIT :offset, :itemsPerPage";

    $stmt = $pdo->prepare($query);

    foreach ($searchTerms as $index => $term) {
        $stmt->bindValue(":search{$index}", '%' . $term . '%', PDO::PARAM_STR);
    }

    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':itemsPerPage', $itemsPerPage, PDO::PARAM_INT);

    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $countQuery = "SELECT COUNT(DISTINCT u.id) AS total FROM users u 
                   LEFT JOIN students s ON u.id = s.user_id
                   LEFT JOIN student_skills ss ON s.user_id = ss.student_id
                   LEFT JOIN skills sk ON ss.skill_id = sk.id
                   WHERE u.user_type = 'student'";

    if (!empty($searchTerms)) {
        $searchConditions = [];
        foreach ($searchTerms as $index => $term) {
            $searchConditions[] = "sk.name LIKE :search{$index}";
        }
        $countQuery .= " AND (" . implode(' OR ', $searchConditions) . ")";
    }

    $countStmt = $pdo->prepare($countQuery);

    foreach ($searchTerms as $index => $term) {
        $countStmt->bindValue(":search{$index}", '%' . $term . '%', PDO::PARAM_STR);
    }

    $countStmt->execute();
    $totalItems = $countStmt->fetchColumn();
    $totalPages = ceil($totalItems / $itemsPerPage);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des données : " . htmlspecialchars($e->getMessage()));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Recruteur</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
            background-color: #f4f5f7;
        }

        .sidebar {
            width: 250px;
            background-color: #f8f9fa;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .sidebar .logo {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .sidebar .logo img {
            width: 120px;
            margin-right: 10px;
        }

        .sidebar .menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar .menu li {
            margin-bottom: 15px;
        }

        .sidebar .menu a {
            text-decoration: none;
            color: #333;
            display: flex;
            align-items: center;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .sidebar .menu a:hover {
            background-color: #007bff;
            color: #fff;
        }

        .sidebar .menu a i {
            margin-right: 10px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
        }


        .navbar {
            height: 60px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #ddd;
        }

        .btn-logout {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .btn-logout:hover {
            background-color: #0056b3;
        }

        .content {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .pagination a {
            padding: 10px 15px;
            margin: 0 5px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #007BFF;
        }
        .navbar {
            height: 60px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #ddd;
        }

        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }
    </style>
</head>
<body>
     <!-- Sidebar -->
     <div class="sidebar">
        <div>
        <div class="logo">
    <img src="Portifica.png" alt="Portifica Logo" class="logo-image">
</div>

<style>
    .logo-image {
        width: 50px; /* Augmentez cette valeur selon la taille désirée */
        height: auto; /* Maintient les proportions */
    }
</style>

            <ul class="menu">
                <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="recruiter_profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
              
            </ul>
        </div>
        
    </div>
    <div class="main-content">
    <div class="navbar">
            <div class="search-bar">
              
            </div>
           
                <div>
            <a href="logout.php" class="btn btn-logout">Déconnexion</a>
        </div>
          
        </div>
        <div class="content">
            <div class="d-flex justify-content-center mb-4">
                <form method="GET" class="w-75">
                    <input type="text" name="search" class="form-control" placeholder="Rechercher par compétences" value="<?php echo htmlspecialchars($search); ?>">
                    <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
                </form>
            </div>
            <div class="row">
                <?php if (!empty($students)): ?>
                    <?php foreach ($students as $student): ?>
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title text-primary"><?php echo htmlspecialchars($student['student_name']); ?></h5>
                                    <p><strong>Email :</strong> <?php echo htmlspecialchars($student['email']); ?></p>
                                    <p><strong>Compétences :</strong> <?php echo htmlspecialchars($student['skills'] ?? 'Aucune compétence'); ?></p>
                                    <?php if ($student['cv']): ?>
                                        <a href="<?php echo htmlspecialchars($student['cv']); ?>" class="btn btn-success">Voir le CV</a>
                                    <?php else: ?>
                                        <p class="text-muted"><em>Aucun CV disponible</em></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Aucun étudiant ne correspond à vos critères de recherche.</p>
                <?php endif; ?>
            </div>
            <div class="pagination d-flex justify-content-center">
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>" class="<?php echo $i == $page ? 'active' : ''; ?> page-link">Page <?php echo $i; ?></a>
                <?php endfor; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
