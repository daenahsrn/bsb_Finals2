<?php
/**
 * About Page - Database-connected version
 * Backstreet Boys Fan Website
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch all band members from database
$membersQuery = "SELECT * FROM members ORDER BY member_number ASC";
$membersStmt = $db->prepare($membersQuery);
$membersStmt->execute();
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>About - Backstreet Boys Members</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="website.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Playfair+Display:wght@700;900&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
</head>

<body>
    <!-- Retro Star Background -->
    <div class="retro-bg-stars"></div>
    <div class="retro-bg-grid"></div>
    
    <section class="header">
        <header id="header">
            <div class="navigators">
                <nav>
                    <a href="home.php" class="home_nav">
                        <span class="bsb-logo-text">BACKSTREET</span>
                        <span class="bsb-logo-accent">BOYS</span>
                    </a>
                    <a href="about.php" class="about_nav">About</a>
                    <a href="topSongs.php" class="topsongs_nav">Top Hits</a>
                    <a href="history.php" class="history">Band's History</a>
                    <a href="dashboard.php" class="login_btn">Dashboard</a>
                </nav>
            </div>
        </header>
    </section>

    <main class="about_main">
        <section class="about_banner">
            <h1>★ THE BACKSTREET BOYS ★</h1>
            <p>Meet the Kings of Pop</p>
            <div class="decorative-line"></div>
        </section>

        <section class="band_members" id="band_Members">
            <?php if (count($members) > 0): ?>
                <?php foreach ($members as $member): ?>
                    <div class="member_card <?php echo $member['is_founding_member'] ? 'founding-member' : ''; ?>">
                        <div class="member_image_container">
                            <img src="image/<?php echo htmlspecialchars($member['image_filename'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($member['full_name']); ?>">
                            <?php if ($member['is_founding_member']): ?>
                                <span class="founding-badge">Founding Member</span>
                            <?php endif; ?>
                        </div>
                        <div class="member_info">
                            <h2><?php echo htmlspecialchars($member['stage_name'] ?: $member['full_name']); ?></h2>
                            <p class="member-role"><?php echo htmlspecialchars($member['role']); ?></p>
                            <p class="member-details">
                                <strong>Born:</strong> <?php echo htmlspecialchars($member['birth_date']); ?><br>
                                <strong>Birthplace:</strong> <?php echo htmlspecialchars($member['birth_place']); ?><br>
                                <strong>Joined:</strong> <?php echo htmlspecialchars($member['joined_year']); ?>
                            </p>
                            <p class="member-description"><?php echo htmlspecialchars($member['description']); ?></p>
                            <span class="status-badge status-<?php echo $member['status']; ?>"><?php echo ucfirst($member['status']); ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-members">No band members available at the moment.</p>
            <?php endif; ?>
        </section>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <span class="bsb-logo-text">BACKSTREET</span>
                <span class="bsb-logo-accent">BOYS</span>
            </div>
            <div class="social-links">
                <a href="https://www.facebook.com/backstreetboys" target="_blank"><img src="image/facebook icon.jpg" alt="Facebook"></a>
                <a href="https://www.instagram.com/backstreetboys" target="_blank"><img src="image/instagram icon.jpg" alt="Instagram"></a>
                <a href="https://x.com/backstreetboys" target="_blank"><img src="image/twitter.jpg" alt="Twitter"></a>
                <a href="https://www.youtube.com/user/bsbofficial" target="_blank"><img src="image/yt icon.jpg" alt="YouTube"></a>
                <a href="https://open.spotify.com/artist/5rSXSAkZ67PYJSvpUpkOr7" target="_blank"><img src="image/spotify icon.jpg" alt="Spotify"></a>
                <a href="https://www.tiktok.com/@backstreetboys" target="_blank"><img src="image/tiktok icon.jpg" alt="TikTok"></a>
            </div>
            <p class="copyright">© 2025 Backstreet Boys Fan Website. Made with ❤️ for BSB fans worldwide.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
