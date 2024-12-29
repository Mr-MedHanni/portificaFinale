<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
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

        /* Navbar */
        .navbar {
            height: 60px;
            background-color: #fff;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 20px;
            border-bottom: 1px solid #ddd;
        }

        .navbar .search-bar {
            width: 300px;
            display: flex;
            align-items: center;
            background-color: #f8f9fa;
            padding: 5px 10px;
            border-radius: 20px;
        }

        .navbar .search-bar input {
            border: none;
            outline: none;
            background: none;
            width: 100%;
            margin-left: 10px;
        }

        .navbar .actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar .actions img {
            width: 30px;
            border-radius: 50%;
        }

        .navbar .btn-logout {
            text-decoration: none;
            color: white;
            background-color: #007bff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }

        .navbar .btn-logout:hover {
            background-color: #0056b3;
        }

        /* Dashboard Content */
        .content {
            padding: 20px;
            flex: 1;
            overflow-y: auto;
        }

        .content h1 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #007bff;
        }

        .card {
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
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
        width: 500px; /* Augmentez cette valeur selon la taille désirée */
        height: auto; /* Maintient les proportions */
    }
</style>

            <ul class="menu">
                <li><a href="#"><i class="fas fa-home"></i> Dashboard</a></li>
                <li><a href="student_profile.php"><i class="fas fa-user"></i> Mon Profil</a></li>
              
            </ul>
        </div>
        
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <div class="navbar">
            <div class="search-bar">
              
            </div>
           
                <div>
            <a href="logout.php" class="btn btn-logout">Déconnexion</a>
        </div>
          
        </div>

        <!-- Content -->
        <div class="content">
            
        
        </div>
    </div>
</body>
</html>
