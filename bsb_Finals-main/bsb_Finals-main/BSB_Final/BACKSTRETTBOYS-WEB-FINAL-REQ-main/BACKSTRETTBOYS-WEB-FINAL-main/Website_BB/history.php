<?php
/**
 * History Page - Database-connected version
 * Backstreet Boys Fan Website
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch timeline events from database
$timelineQuery = "SELECT * FROM timeline_events ORDER BY event_year ASC";
$timelineStmt = $db->prepare($timelineQuery);
$timelineStmt->execute();
$events = $timelineStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>History - Backstreet Boys Timeline</title>
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
                    <a href="login.php" class="login_btn">Dashboard</a>
                </nav>
            </div>
        </header>
    </section>

    <main class="history_main">
        <section class="history_banner">
            <h1>★ BAND HISTORY ★</h1>
            <p>30 Years of Musical Excellence</p>
            <div class="decorative-line"></div>
        </section>

        <section class="timeline_container">
            <?php if (count($events) > 0): ?>
                <div class="timeline">
                    <?php foreach ($events as $event): ?>
                        <div class="timeline-item <?php echo $event['position']; ?> <?php echo $event['is_highlight'] ? 'highlight' : ''; ?>">
                            <div class="timeline-content">
                                <span class="timeline-year"><?php echo htmlspecialchars($event['event_year']); ?></span>
                                <h3><?php echo htmlspecialchars($event['title']); ?></h3>
                                <p><?php echo htmlspecialchars($event['description']); ?></p>
                                <?php if ($event['is_highlight']): ?>
                                    <span class="highlight-badge">★ Highlight</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="no-events">No timeline events available at the moment.</p>
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
