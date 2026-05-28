<?php
/**
 * Home Page - Database-connected version
 * Backstreet Boys Fan Website
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch top hit songs from database
$songsQuery = "SELECT * FROM songs WHERE is_hit = 1 ORDER BY hit_rank ASC LIMIT 10";
$songsStmt = $db->prepare($songsQuery);
$songsStmt->execute();
$songs = $songsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backstreet Boys - The Kings of Pop</title>
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

    <main class="contents_home">
        <!-- Hero Section with Retro Frame -->
        <section class="hero_banner">
            <div class="hero-retro-frame">
                <div class="hero-corner top-left"></div>
                <div class="hero-corner top-right"></div>
                <div class="hero-corner bottom-left"></div>
                <div class="hero-corner bottom-right"></div>
                <img src="backstreetBoys.jpg" alt="Backstreet Boys Banner">
                <div class="hero-overlay"></div>
            </div>
            <div class="hero-title-section">
                <h1 class="hero-main-title">BACKSTREET BOYS</h1>
                <p class="hero-subtitle">★ The Kings of Pop Since 1993 ★</p>
                <div class="hero-decorative-line"></div>
            </div>
        </section>

        <!-- Music Links Section - Retro Jukebox Style -->
        <section class="music_Home">
            <div class="jukebox-header">
                <div class="jukebox-lights"></div>
                <h3 class="jukebox-title">🎵 CLASSIC HITS 🎵</h3>
                <div class="jukebox-lights"></div>
            </div>
            <div class="music_container">
                <?php if (count($songs) > 0): ?>
                    <?php foreach ($songs as $index => $song): ?>
                        <a href="<?php echo htmlspecialchars($song['youtube_url'] ?? '#'); ?>" class="hit-song" target="_blank" rel="noopener noreferrer">
                            <span class="song-number"><?php echo str_pad($index + 1, 2, '0', STR_PAD_LEFT); ?></span>
                            <span class="song-title"><?php echo htmlspecialchars($song['title']); ?></span>
                            <span class="song-year"><?php echo htmlspecialchars($song['release_year']); ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="no-songs">No hit songs available at the moment.</p>
                <?php endif; ?>
            </div>
            <div class="music-decoration">
                <span class="music-note">♪</span>
                <span class="music-note">♫</span>
                <span class="music-note">♪</span>
            </div>
        </section>

        <!-- Preview Cards - Vintage Album Style -->
        <section class="previews">
            <div class="previews-header">
                <h2>LATEST ALBUMS</h2>
                <div class="decorative-dots">
                    <span></span><span></span><span></span>
                </div>
            </div>
            <div class="preview-cards-container">
                <a href="topSongs.php" class="preview-card album-preview">
                    <div class="card-vintage-frame">
                        <div class="frame-corner top-left"></div>
                        <div class="frame-corner top-right"></div>
                        <div class="frame-corner bottom-left"></div>
                        <div class="frame-corner bottom-right"></div>
                        <img src="image/millenium 2.0.jpg" alt="Millennium 2.0">
                    </div>
                    <div class="preview-info">
                        <h3>Millennium 2.0</h3>
                        <p>25th Anniversary Edition</p>
                    </div>
                </a>
                <a href="topSongs.php" class="preview-card album-preview">
                    <div class="card-vintage-frame">
                        <div class="frame-corner top-left"></div>
                        <div class="frame-corner top-right"></div>
                        <div class="frame-corner bottom-left"></div>
                        <div class="frame-corner bottom-right"></div>
                        <img src="image/christmasAlbum.jpg" alt="Christmas Album">
                    </div>
                    <div class="preview-info">
                        <h3>A Very Backstreet Christmas</h3>
                        <p>Holiday Collection</p>
                    </div>
                </a>
                <a href="topSongs.php" class="preview-card album-preview">
                    <div class="card-vintage-frame">
                        <div class="frame-corner top-left"></div>
                        <div class="frame-corner top-right"></div>
                        <div class="frame-corner bottom-left"></div>
                        <div class="frame-corner bottom-right"></div>
                        <img src="image/DNAAlbum.jpg" alt="DNA">
                    </div>
                    <div class="preview-info">
                        <h3>DNA</h3>
                        <p>Genetic Masterpiece</p>
                    </div>
                </a>
            </div>
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
                <a href="https://www.facebook.com/backstreetboys" target="_blank" rel="noopener noreferrer">
                    <img src="image/facebook icon.jpg" alt="Facebook">
                </a>
                <a href="https://www.instagram.com/backstreetboys" target="_blank" rel="noopener noreferrer">
                    <img src="image/instagram icon.jpg" alt="Instagram">
                </a>
                <a href="https://x.com/backstreetboys" target="_blank" rel="noopener noreferrer">
                    <img src="image/twitter.jpg" alt="Twitter">
                </a>
                <a href="https://www.youtube.com/user/bsbofficial" target="_blank" rel="noopener noreferrer">
                    <img src="image/yt icon.jpg" alt="YouTube">
                </a>
                <a href="https://open.spotify.com/artist/5rSXSAkZ67PYJSvpUpkOr7" target="_blank" rel="noopener noreferrer">
                    <img src="image/spotify icon.jpg" alt="Spotify">
                </a>
                <a href="https://www.tiktok.com/@backstreetboys" target="_blank" rel="noopener noreferrer">
                    <img src="image/tiktok icon.jpg" alt="TikTok">
                </a>
            </div>
            <p class="copyright">© 2025 Backstreet Boys Fan Website. Made with ❤️ for BSB fans worldwide.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
