<?php
/**
 * Top Songs Page - Database-connected version
 * Backstreet Boys Fan Website
 */

include_once 'config/database.php';

$database = new Database();
$db = $database->getConnection();

// Fetch all albums with track counts
$albumsQuery = "SELECT a.*, 
                (SELECT COUNT(*) FROM album_tracks WHERE album_id = a.album_id) as track_count
                FROM albums a 
                ORDER BY release_year DESC, title ASC";
$albumsStmt = $db->prepare($albumsQuery);
$albumsStmt->execute();
$albums = $albumsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Backstreet Boys - Top Hits & Albums</title>
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

    <main class="topsongs_main">
        <section class="topsongs_banner">
            <h1>🎵 ALBUM COLLECTION 🎵</h1>
            <p>From Millennium to DNA - A Journey Through Music</p>
            <div class="decorative-line"></div>
        </section>

        <section class="albums_container">
            <?php if (count($albums) > 0): ?>
                <?php foreach ($albums as $album): ?>
                    <div class="album-card <?php echo $album['is_highlight'] ? 'highlighted' : ''; ?>">
                        <div class="album-artwork">
                            <img src="image/<?php echo htmlspecialchars($album['cover_image_filename'] ?? 'default.jpg'); ?>" alt="<?php echo htmlspecialchars($album['title']); ?>">
                            <?php if ($album['is_featured']): ?>
                                <span class="featured-badge">Featured</span>
                            <?php endif; ?>
                        </div>
                        <div class="album-info">
                            <h2><?php echo htmlspecialchars($album['title']); ?></h2>
                            <p class="release-year"><?php echo htmlspecialchars($album['release_year']); ?></p>
                            <p class="track-count"><?php echo $album['track_count']; ?> Tracks</p>
                            
                            <?php
                            // Fetch tracks for this album
                            $tracksQuery = "SELECT * FROM album_tracks WHERE album_id = ? ORDER BY track_number ASC";
                            $tracksStmt = $db->prepare($tracksQuery);
                            $tracksStmt->execute([$album['album_id']]);
                            $tracks = $tracksStmt->fetchAll(PDO::FETCH_ASSOC);
                            ?>
                            
                            <?php if (count($tracks) > 0): ?>
                                <div class="tracklist">
                                    <h3>Track List:</h3>
                                    <ol>
                                        <?php foreach ($tracks as $track): ?>
                                            <li>
                                                <span class="track-title"><?php echo htmlspecialchars($track['title']); ?></span>
                                                <?php if ($track['is_single']): ?>
                                                    <span class="single-badge">Single</span>
                                                <?php endif; ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ol>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="no-albums">No albums available at the moment.</p>
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
