<?php
/**
 * Admin Dashboard - Backstreet Boys Fan Website
 * Database-connected version with CRUD functionality
 */

session_start();

// Check if admin is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

include_once 'config/database.php';
include_once 'models/Member.php';
include_once 'models/Album.php';

$database = new Database();
$db = $database->getConnection();

// Initialize models
$member = new Member($db);
$album = new Album($db);

// Get counts for stats
$membersCount = $member->getCount();
$albumsCount = $album->getCount();

// Fetch all members
$membersStmt = $member->getAll();
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch all albums with tracks
$albumsStmt = $album->getAll();
$albums = $albumsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Backstreet Boys</title>
    <link rel="stylesheet" href="website.css">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: linear-gradient(135deg, #0a0a1a 0%, #1a1a3e 50%, #0f3460 100%);
            min-height: 100vh;
            color: #e4e4e4;
            position: relative;
            overflow-x: hidden;
        }

        /* Retro Background Elements */
        .retro-bg-stars {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                radial-gradient(2px 2px at 20px 30px, rgba(255, 255, 255, 0.8), transparent),
                radial-gradient(2px 2px at 40px 70px, rgba(255, 255, 255, 0.6), transparent),
                radial-gradient(1px 1px at 90px 40px, rgba(255, 255, 255, 0.7), transparent),
                radial-gradient(2px 2px at 160px 120px, rgba(255, 255, 255, 0.5), transparent);
            background-size: 200px 200px;
            animation: twinkle 5s infinite;
            pointer-events: none;
            z-index: 0;
        }

        .retro-bg-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(139, 92, 246, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(139, 92, 246, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 0;
        }

        @keyframes twinkle {
            0%, 100% { opacity: 0.8; }
            50% { opacity: 1; }
        }

        /* Header */
        .dashboard-header {
            background: rgba(20, 20, 40, 0.9);
            border-bottom: 2px solid rgba(139, 92, 246, 0.3);
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: relative;
            z-index: 10;
            backdrop-filter: blur(20px);
        }

        .logo {
            font-family: 'Cinzel', serif;
            font-size: 1.8rem;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: 2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .logo span {
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .logo i {
            color: #a78bfa;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .admin-badge {
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .logout-btn {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            letter-spacing: 0.5px;
            box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
        }

        .logout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(239, 68, 68, 0.5);
        }

        /* Main Container */
        .dashboard-container {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 24px;
            padding: 30px 40px;
            position: relative;
            z-index: 10;
            max-width: 1600px;
            margin: 0 auto;
        }

        /* Sidebar */
        .sidebar {
            background: rgba(30, 30, 60, 0.6);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 20px;
            padding: 28px;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
            height: fit-content;
            position: sticky;
            top: 30px;
        }

        .profile-section {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 28px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }

        .profile-avatar {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 18px;
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            font-weight: 700;
            color: #fff;
            box-shadow: 0 4px 20px rgba(167, 139, 250, 0.4);
            border: 3px solid rgba(255, 255, 255, 0.1);
        }

        .profile-name {
            font-family: 'Cinzel', serif;
            font-size: 1.2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 6px;
        }

        .profile-role {
            font-family: 'DM Sans', sans-serif;
            color: #a78bfa;
            font-size: 0.85rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-menu {
            list-style: none;
        }

        .nav-item {
            margin-bottom: 10px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            background: transparent;
            border: none;
            border-radius: 12px;
            color: #d1d5db;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 3px;
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .nav-link:hover::before,
        .nav-link.active::before {
            transform: scaleY(1);
        }

        .nav-link:hover,
        .nav-link.active {
            background: rgba(139, 92, 246, 0.15);
            color: #a78bfa;
        }

        .nav-link i {
            color: #9ca3af;
            font-size: 1.1rem;
            width: 22px;
            text-align: center;
        }

        .nav-link:hover i,
        .nav-link.active i {
            color: #a78bfa;
        }

        /* Main Content */
        .main-content {
            display: flex;
            flex-direction: column;
            gap: 28px;
        }

        .content-card {
            background: rgba(30, 30, 60, 0.6);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 20px;
            padding: 32px;
            backdrop-filter: blur(20px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }

        .card-title {
            font-family: 'Cinzel', serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .card-title i {
            color: #a78bfa;
        }

        .add-new-btn {
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            border: none;
            padding: 12px 24px;
            border-radius: 10px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 2px 12px rgba(167, 139, 250, 0.3);
        }

        .add-new-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(167, 139, 250, 0.5);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: rgba(139, 92, 246, 0.1);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 16px;
            padding: 26px;
            text-align: center;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            background: rgba(139, 92, 246, 0.15);
            border-color: rgba(139, 92, 246, 0.4);
            box-shadow: 0 8px 24px rgba(139, 92, 246, 0.2);
        }

        .stat-icon {
            font-size: 2.2rem;
            color: #a78bfa;
            margin-bottom: 14px;
        }

        .stat-value {
            font-family: 'Cinzel', serif;
            font-size: 2rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .stat-label {
            font-family: 'DM Sans', sans-serif;
            color: #9ca3af;
            font-size: 0.85rem;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Members Table */
        .members-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .members-table th,
        .members-table td {
            padding: 16px;
            text-align: left;
            border-bottom: 1px solid rgba(139, 92, 246, 0.1);
        }

        .members-table th {
            font-family: 'DM Sans', sans-serif;
            color: #a78bfa;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .members-table td {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            color: #d1d5db;
        }

        .members-table tr:hover {
            background: rgba(139, 92, 246, 0.05);
        }

        .member-avatar {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Cinzel', serif;
            font-weight: 700;
            color: #fff;
            font-size: 1.1rem;
        }

        .member-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .member-name {
            font-weight: 600;
            color: #ffffff;
        }

        .status-badge {
            padding: 5px 14px;
            border-radius: 20px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            border: 1px solid rgba(34, 197, 94, 0.3);
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 36px;
            height: 36px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
        }

        .action-btn.edit {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: #fff;
        }

        .action-btn.edit:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4);
        }

        .action-btn.delete {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #fff;
        }

        .action-btn.delete:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4);
        }

        .view-site-btn {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: #fff;
            padding: 12px 24px;
            border-radius: 10px;
            text-decoration: none;
            font-family: 'DM Sans', sans-serif;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .view-site-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.4);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(8px);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            padding: 20px;
        }

        .modal-overlay.active {
            display: flex;
        }

        .modal-container {
            background: linear-gradient(135deg, rgba(30, 30, 60, 0.95) 0%, rgba(20, 20, 40, 0.98) 100%);
            border: 1px solid rgba(139, 92, 246, 0.3);
            border-radius: 24px;
            padding: 36px;
            max-width: 600px;
            width: 100%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            position: relative;
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 28px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(139, 92, 246, 0.2);
        }

        .modal-title {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: #ffffff;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .close-modal {
            background: transparent;
            border: none;
            color: #9ca3af;
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .close-modal:hover {
            background: rgba(139, 92, 246, 0.1);
            color: #a78bfa;
        }

        .form-group {
            margin-bottom: 22px;
        }

        .form-label {
            display: block;
            font-family: 'DM Sans', sans-serif;
            color: #a78bfa;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 14px 18px;
            background: rgba(139, 92, 246, 0.05);
            border: 1px solid rgba(139, 92, 246, 0.2);
            border-radius: 12px;
            color: #ffffff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: #a78bfa;
            background: rgba(139, 92, 246, 0.1);
            box-shadow: 0 0 0 3px rgba(167, 139, 250, 0.1);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .modal-footer {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 28px;
            padding-top: 24px;
            border-top: 1px solid rgba(139, 92, 246, 0.2);
        }

        .btn-cancel {
            background: transparent;
            border: 1px solid rgba(139, 92, 246, 0.3);
            padding: 12px 28px;
            border-radius: 10px;
            color: #9ca3af;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-cancel:hover {
            border-color: #a78bfa;
            color: #a78bfa;
        }

        .btn-primary {
            background: linear-gradient(135deg, #a78bfa 0%, #60a5fa 100%);
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(167, 139, 250, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(167, 139, 250, 0.5);
        }

        .btn-danger {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            border: none;
            padding: 12px 32px;
            border-radius: 10px;
            color: #fff;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 12px rgba(239, 68, 68, 0.3);
        }

        .btn-danger:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 20px rgba(239, 68, 68, 0.5);
        }

        /* Albums Grid */
        .albums-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .album-card {
            background: rgba(139, 92, 246, 0.05);
            border: 1px solid rgba(139, 92, 246, 0.1);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }

        .album-card:hover {
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateY(-4px);
        }

        .album-cover {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 12px;
            margin-bottom: 16px;
            background: rgba(139, 92, 246, 0.1);
        }

        .album-title {
            font-family: 'Cinzel', serif;
            color: #ffffff;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .album-year {
            color: #a78bfa;
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        .album-tracks {
            color: #9ca3af;
            font-size: 0.85rem;
        }

        @media (max-width: 1024px) {
            .dashboard-container {
                grid-template-columns: 1fr;
            }

            .sidebar {
                position: static;
                order: 2;
            }
        }

        @media (max-width: 768px) {
            .dashboard-header {
                flex-direction: column;
                gap: 16px;
                padding: 16px 20px;
            }

            .dashboard-container {
                padding: 20px;
            }

            .content-card {
                padding: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Retro Background Elements -->
    <div class="retro-bg-stars"></div>
    <div class="retro-bg-grid"></div>

    <!-- Header -->
    <header class="dashboard-header">
        <h1 class="logo"><i class="fas fa-music"></i> <span>BACKSTREET</span> BOYS</h1>
        <div class="user-info">
            <span class="admin-badge">Admin</span>
            <a href="home.php" class="view-site-btn">
                <i class="fas fa-external-link-alt"></i> View Website
            </a>
            <a href="logout.php" class="logout-btn">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </header>

    <!-- Main Dashboard -->
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="profile-section">
                <div class="profile-avatar">AD</div>
                <h2 class="profile-name">Admin Dashboard</h2>
                <p class="profile-role">Site Administrator</p>
            </div>

            <ul class="nav-menu">
                <li class="nav-item">
                    <a href="#members-section" class="nav-link active">
                        <i class="fas fa-users"></i>
                        <span>Members (Characters)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#albums-section" class="nav-link">
                        <i class="fas fa-compact-disc"></i>
                        <span>Albums (Movies)</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="home.php" class="nav-link">
                        <i class="fas fa-home"></i>
                        <span>View Site</span>
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Members Section -->
            <div class="content-card" id="members-section">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-users"></i>
                        Members Management (Characters)
                    </h2>
                    <button class="add-new-btn" onclick="openMemberModal()">
                        <i class="fas fa-plus"></i> Add Member
                    </button>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-value"><?php echo $membersCount; ?></div>
                        <div class="stat-label">Total Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value"><?php echo count(array_filter($members, fn($m) => $m['is_founding_member'])); ?></div>
                        <div class="stat-label">Founding Members</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="stat-value"><?php echo count(array_filter($members, fn($m) => $m['status'] === 'active')); ?></div>
                        <div class="stat-label">Active</div>
                    </div>
                </div>

                <!-- Members Table -->
                <table class="members-table">
                    <thead>
                        <tr>
                            <th>Member</th>
                            <th>Role</th>
                            <th>Born</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="membersTableBody">
                        <?php foreach ($members as $member): ?>
                        <tr>
                            <td>
                                <div class="member-info">
                                    <div class="member-avatar">
                                        <?php echo strtoupper(substr($member['full_name'], 0, 1)); ?>
                                    </div>
                                    <div>
                                        <div class="member-name"><?php echo htmlspecialchars($member['full_name']); ?></div>
                                        <small style="color: #9ca3af;"><?php echo htmlspecialchars($member['stage_name'] ?? ''); ?></small>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo htmlspecialchars($member['role']); ?></td>
                            <td><?php echo $member['birth_date'] ? date('M d, Y', strtotime($member['birth_date'])) : 'N/A'; ?></td>
                            <td><span class="status-badge status-active"><?php echo htmlspecialchars($member['status']); ?></span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn edit" onclick="editMember(<?php echo $member['member_id']; ?>)" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteMember(<?php echo $member['member_id']; ?>, '<?php echo htmlspecialchars($member['full_name']); ?>')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <!-- Albums Section -->
            <div class="content-card" id="albums-section">
                <div class="card-header">
                    <h2 class="card-title">
                        <i class="fas fa-compact-disc"></i>
                        Albums Management (Movies)
                    </h2>
                    <button class="add-new-btn" onclick="openAlbumModal()">
                        <i class="fas fa-plus"></i> Add Album
                    </button>
                </div>

                <!-- Stats -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-album"></i>
                        </div>
                        <div class="stat-value"><?php echo $albumsCount; ?></div>
                        <div class="stat-label">Total Albums</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-music"></i>
                        </div>
                        <div class="stat-value"><?php echo array_sum(array_column($albums, 'track_count')); ?></div>
                        <div class="stat-label">Total Tracks</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-highlighter"></i>
                        </div>
                        <div class="stat-value"><?php echo count(array_filter($albums, fn($a) => $a['is_highlight'])); ?></div>
                        <div class="stat-label">Featured</div>
                    </div>
                </div>

                <!-- Albums Grid -->
                <div class="albums-grid">
                    <?php foreach ($albums as $album): ?>
                    <div class="album-card">
                        <?php if ($album['cover_image_filename']): ?>
                        <img src="image/<?php echo htmlspecialchars($album['cover_image_filename']); ?>" alt="<?php echo htmlspecialchars($album['title']); ?>" class="album-cover" onerror="this.style.display='none'">
                        <?php endif; ?>
                        <h3 class="album-title"><?php echo htmlspecialchars($album['title']); ?></h3>
                        <p class="album-year">Released: <?php echo $album['release_date'] ? date('M d, Y', strtotime($album['release_date'])) : $album['release_year']; ?></p>
                        <p class="album-tracks">
                            <i class="fas fa-music"></i> <?php echo $album['track_count']; ?> tracks
                        </p>
                        <div class="action-buttons" style="margin-top: 16px;">
                            <button class="action-btn edit" onclick="editAlbum(<?php echo $album['album_id']; ?>)" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="action-btn delete" onclick="deleteAlbum(<?php echo $album['album_id']; ?>, '<?php echo htmlspecialchars($album['title']); ?>')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Member Modal -->
    <div class="modal-overlay" id="memberModal">
        <div class="modal-container">
            <div class="modal-header">
                <h3 class="modal-title">
                    <i class="fas fa-user-edit"></i>
                    <span id="memberModalTitle">Edit Member</span>
                </h3>
                <button class="close-modal" onclick="closeMemberModal()">&times;</button>
            </div>
            <form id="memberForm" onsubmit="saveMember(event)">
                <input type="hidden" id="memberId">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" class="form-input" id="memberName" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Stage Name</label>
                        <input type="text" class="form-input" id="memberStageName">
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Birth Date</label>
                        <input type="date" class="form-input" id="memberBirthDate">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="memberStatus" required>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="former">Former</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea class="form-textarea" id="memberDescription"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeMemberModal()">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Save Member
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Member Functions
        function openMemberModal() {
            document.getElementById('memberModalTitle').textContent = 'Add New Member';
            document.getElementById('memberForm').reset();
            document.getElementById('memberId').value = '';
            document.getElementById('memberModal').classList.add('active');
        }

        function closeMemberModal() {
            document.getElementById('memberModal').classList.remove('active');
        }

        function editMember(id) {
            // Fetch member data and populate modal
            fetch(`api/members.php?action=getById&id=${id}`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('memberModalTitle').textContent = 'Edit Member';
                    document.getElementById('memberId').value = data.member_id;
                    document.getElementById('memberName').value = data.full_name;
                    document.getElementById('memberStageName').value = data.stage_name || '';
                    document.getElementById('memberBirthDate').value = data.birth_date || '';
                    document.getElementById('memberStatus').value = data.status;
                    document.getElementById('memberDescription').value = data.description || '';
                    document.getElementById('memberModal').classList.add('active');
                });
        }

        function saveMember(event) {
            event.preventDefault();
            
            const id = document.getElementById('memberId').value;
            const memberData = {
                member_number: parseInt(document.getElementById('memberName').value ? members.length + 1 : 1),
                full_name: document.getElementById('memberName').value,
                stage_name: document.getElementById('memberStageName').value,
                birth_date: document.getElementById('memberBirthDate').value,
                status: document.getElementById('memberStatus').value,
                description: document.getElementById('memberDescription').value
            };

            const url = id ? `api/members.php?id=${id}` : 'api/members.php';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(memberData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Member saved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the member.');
            });
        }

        function deleteMember(id, name) {
            if (confirm(`Are you sure you want to delete ${name}? This action cannot be undone.`)) {
                fetch(`api/members.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Member deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the member.');
                });
            }
        }

        // Album Functions
        function openAlbumModal() {
            alert('Album modal functionality - implement similar to member modal');
        }

        function editAlbum(id) {
            alert('Edit album functionality for ID: ' + id);
        }

        function deleteAlbum(id, title) {
            if (confirm(`Are you sure you want to delete "${title}"? This will also delete all tracks.`)) {
                fetch(`api/albums.php?id=${id}`, {
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Album deleted successfully!');
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the album.');
                });
            }
        }
    </script>
</body>
</html>
