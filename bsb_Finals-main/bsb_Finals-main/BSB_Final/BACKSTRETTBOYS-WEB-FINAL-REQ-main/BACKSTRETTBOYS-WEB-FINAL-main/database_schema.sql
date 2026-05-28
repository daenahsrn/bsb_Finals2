-- Backstreet Boys Fan Website Database Structure
-- Created based on existing HTML content analysis

-- Drop existing tables if they exist
DROP TABLE IF EXISTS album_tracks;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS member_history;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS timeline_events;

-- ============================================
-- USERS TABLE (for login/register system)
-- ============================================
CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(100),
    favorite_member_id INT,
    status ENUM('active', 'inactive', 'premium') DEFAULT 'active',
    user_type ENUM('fan', 'superfan', 'vip', 'admin') DEFAULT 'fan',
    join_date DATE,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (favorite_member_id) REFERENCES members(member_id)
);

-- ============================================
-- MEMBERS TABLE (Band Members - Characters)
-- Based on about.html band_Members section
-- ============================================
CREATE TABLE members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    member_number INT NOT NULL UNIQUE,
    full_name VARCHAR(100) NOT NULL,
    stage_name VARCHAR(50),
    birth_date DATE,
    birth_place VARCHAR(255),
    role VARCHAR(100) DEFAULT 'Vocalist',
    description TEXT,
    image_filename VARCHAR(255),
    is_founding_member BOOLEAN DEFAULT FALSE,
    joined_year INT,
    left_year INT NULL,
    status ENUM('active', 'inactive', 'former') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- ALBUMS TABLE (Movies/Top Songs equivalent)
-- Based on topSongs.html album cards
-- ============================================
CREATE TABLE albums (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    release_date DATE,
    release_year INT NOT NULL,
    cover_image_filename VARCHAR(255),
    description TEXT,
    is_featured BOOLEAN DEFAULT FALSE,
    is_highlight BOOLEAN DEFAULT FALSE,
    sales_count INT DEFAULT 0,
    certification VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- ALBUM TRACKS TABLE
-- Based on tracklist in topSongs.html
-- ============================================
CREATE TABLE album_tracks (
    track_id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT NOT NULL,
    track_number INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    duration TIME NULL,
    is_single BOOLEAN DEFAULT FALSE,
    youtube_url VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_id) REFERENCES albums(album_id) ON DELETE CASCADE
);

-- ============================================
-- MEMBER HISTORY TABLE
-- For storing member-specific history/timeline
-- ============================================
CREATE TABLE member_history (
    history_id INT AUTO_INCREMENT PRIMARY KEY,
    member_id INT NOT NULL,
    event_year INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    event_type ENUM('personal', 'career', 'award', 'milestone') DEFAULT 'career',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (member_id) REFERENCES members(member_id) ON DELETE CASCADE
);

-- ============================================
-- TIMELINE EVENTS TABLE
-- Based on history.html timeline items
-- ============================================
CREATE TABLE timeline_events (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_year INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    era ENUM('90s', '00s', '10s', '20s') DEFAULT '90s',
    is_highlight BOOLEAN DEFAULT FALSE,
    position ENUM('left', 'right') DEFAULT 'left',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- ============================================
-- SONGS TABLE (Top Hits)
-- Based on home.html music_Home section
-- ============================================
CREATE TABLE songs (
    song_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    release_year INT,
    album_id INT,
    youtube_url VARCHAR(500),
    is_hit BOOLEAN DEFAULT TRUE,
    hit_rank INT,
    play_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (album_id) REFERENCES albums(album_id) ON DELETE SET NULL
);

-- ============================================
-- SOCIAL MEDIA LINKS TABLE
-- Based on footer social links
-- ============================================
CREATE TABLE social_links (
    social_id INT AUTO_INCREMENT PRIMARY KEY,
    platform VARCHAR(50) NOT NULL,
    url VARCHAR(500) NOT NULL,
    icon_filename VARCHAR(255),
    display_order INT DEFAULT 0,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ============================================
-- INSERT SAMPLE DATA
-- ============================================

-- Insert Band Members (from about.html)
INSERT INTO members (member_number, full_name, stage_name, birth_date, birth_place, role, description, image_filename, is_founding_member, joined_year, status) VALUES
(1, 'AJ McLean', 'AJ', '1978-01-09', 'West Palm Beach, Florida', 'Vocalist', 'Founding member and vocalist. Born January 9, 1978, in West Palm Beach, Florida. Contributed to the "It Gets Better Project."', 'aJMclean.jpg', TRUE, 1993, 'active'),
(2, 'Nick Carter', 'Nick', '1980-01-28', 'Jamestown, New York', 'Lead Vocalist', 'Lead vocalist. Born January 28, 1980, in Jamestown, New York. The youngest member with three siblings.', 'nickCarter.jpg', TRUE, 1993, 'active'),
(3, 'Kevin Richardson', 'Kevin', '1971-10-03', 'Lexington, Kentucky', 'Vocalist', 'Born October 3, 1971, in Lexington, Kentucky. Cousin of Brian Littrell. Grew up on a 10-acre farm with his family.', 'kevinRichardson.jpg', TRUE, 1993, 'active'),
(4, 'Brian Littrell', 'Brian', '1975-02-20', 'Lexington, Kentucky', 'Vocalist', 'Singer, songwriter, and actor. Born February 20, 1975, in Lexington, Kentucky. Released solo album "Welcome Home" (2006). Raised in a Baptist family.', 'brianlittrell.jpg', TRUE, 1993, 'active'),
(5, 'Howie Dorough', 'Howie D', '1973-08-22', 'Orlando, Florida', 'Vocalist', '"Howie D" - Singer and actor. Born August 22, 1973, in Orlando, Florida. Youngest of 6 siblings. Met AJ McLean in Orlando.', 'howieDorough.jpg', TRUE, 1993, 'active');

-- Insert Albums (from topSongs.html)
INSERT INTO albums (title, release_date, release_year, cover_image_filename, is_featured, is_highlight) VALUES
('Millennium 2.0', '2025-07-11', 2025, 'millenium 2.0.jpg', TRUE, FALSE),
('A Very Backstreet Christmas', '2022-10-14', 2022, 'christmasAlbum.jpg', TRUE, FALSE),
('DNA', '2019-01-25', 2019, 'DNAAlbum.jpg', TRUE, FALSE),
('Black & Blue', '2000-11-21', 2000, 'black&blue.jpg', TRUE, FALSE),
('Millennium', '1999-05-18', 1999, 'millenium.jpg', TRUE, TRUE);

-- Insert Album Tracks (from topSongs.html tracklists)
INSERT INTO album_tracks (album_id, track_number, title, is_single) VALUES
-- Millennium 2.0 tracks
(1, 1, 'Larger Than Life', FALSE),
(1, 2, 'I Want It That Way', TRUE),
(1, 3, 'Show Me the Meaning of Being Lonely', TRUE),
(1, 4, "It's Gotta Be You", FALSE),
(1, 5, 'I Need You Tonight', FALSE),
-- A Very Backstreet Christmas tracks
(2, 1, 'White Christmas', FALSE),
(2, 2, 'The Christmas Song', FALSE),
(2, 3, 'Winter Wonderland', FALSE),
(2, 4, 'Have Yourself A Merry Little Christmas', FALSE),
(2, 5, 'Last Christmas', FALSE),
-- DNA tracks
(3, 1, "Don't Go Breaking My Heart", TRUE),
(3, 2, 'Nobody Else', FALSE),
(3, 3, 'Breathe', FALSE),
(3, 4, 'New Love', FALSE),
(3, 5, 'Passionate', FALSE),
-- Black & Blue tracks
(4, 1, 'The Call', TRUE),
(4, 2, 'Shape of My Heart', TRUE),
(4, 3, 'Get Another Boyfriend', FALSE),
(4, 4, 'Shining Star', FALSE),
(4, 5, 'I Promise You (With Everything I Am)', FALSE),
-- Millennium tracks
(5, 1, 'As Long As You Love Me', TRUE),
(5, 2, "Everybody (Backstreet's Back)", TRUE),
(5, 3, 'All I Have to Give', TRUE),
(5, 4, "Get Down (You're the One for Me)", TRUE),
(5, 5, 'If You Want It To Be Good Girl (Get Yourself a Bad Boy)', FALSE);

-- Insert Timeline Events (from history.html)
INSERT INTO timeline_events (event_year, title, description, era, is_highlight, position) VALUES
(1993, 'The Beginning', 'Formed in Orlando, Florida by Lou Pearlman. Original members: AJ McLean, Howie Dorough, Nick Carter, Kevin Richardson, and Brian Littrell. First major performance at SeaWorld to 3,000 fans.', '90s', FALSE, 'left'),
(1996, 'International Debut', 'International debut album released. Won MTV Select Award for "Get Down (You're The One For Me)" at the MTV European Music Awards.', '90s', FALSE, 'right'),
(1997, "Backstreet's Back", 'Released second album "Backstreet\'s Back." Won MTV Select Award for "As Long As You Love Me."', '90s', FALSE, 'left'),
(1998, 'Global Domination', 'Won Billboard Award for Group Album of the Year, MuchMusic Video Award for Favorite International Group, MTV VMA for Best Group Video ("Everybody"), and World Music Award for World''s Best-Selling Dance Artist.', '90s', TRUE, 'right'),
(1999, 'Peak Success', 'Swept awards including Teen Choice, Kids'' Choice, and American Music Awards. Dominated Billboard Music Awards with Artist of the Year, Album of the Year, and multiple other categories.', '90s', TRUE, 'left'),
(2000, 'Black & Blue Era', 'Released fourth album "Black & Blue," selling 1.6 million copies in the US first week. Later certified platinum.', '00s', FALSE, 'right'),
(2006, 'A Chapter Closes', 'Kevin Richardson announces his departure from the group.', '00s', FALSE, 'left'),
(2011, 'NKOTBSB Supergroup', 'NKOTBSB supergroup formed with New Kids on the Block. Album debuted at #7 on the US charts.', '10s', FALSE, 'right'),
(2012, 'Kevin Returns', 'Kevin Richardson returns. Performed on Good Morning America''s Summer Concert Series to the largest crowd in GMA history.', '10s', TRUE, 'left'),
(2013, '20th Anniversary', 'Celebrated 20th Anniversary. Released "In A World Like This," debuting at #5 on Billboard 200—making them the first act since Sade with nine US top 10 albums.', '10s', FALSE, 'right'),
(2014, 'Hollywood Recognition', 'Won MTV Movie Award for Best Musical Moment ("This Is The End"). Inducted into Walk of Fame in Locarno, Switzerland.', '10s', FALSE, 'left'),
(2015, 'Hall of Fame', 'Brian Littrell and Kevin Richardson inducted into the Kentucky Music Hall of Fame.', '10s', FALSE, 'right'),
(2018, 'New Music', 'Released "Don''t Go Breaking My Heart," their first single in five years.', '10s', FALSE, 'left'),
(2022, 'Christmas Album', '"A Very Backstreet Christmas" released—their tenth album and first Christmas album. Debuted at #1 on Billboard Top Holiday Albums and #17 on Billboard 200.', '20s', FALSE, 'right'),
(2024, '30th Anniversary', 'Celebrated 30th anniversary with fans in Cancun, Mexico.', '20s', FALSE, 'left'),
(2025, 'Millennium 2.0', 'Millennium 2.0 announced on Today Show, releasing July 11th to celebrate the 25th anniversary of their 5-time GRAMMY-nominated chart-topping album.', '20s', TRUE, 'right');

-- Insert Top Hit Songs (from home.html)
INSERT INTO songs (title, release_year, youtube_url, is_hit, hit_rank) VALUES
('I Want It That Way', 1999, 'https://www.youtube.com/watch?v=VVB6FI8QTFg&list=RDVVB6FI8QTFg&start_radio=1', TRUE, 1),
('As Long As You Love Me', 1997, 'https://www.youtube.com/watch?v=0Gl2QnHNpkA&list=RD0Gl2QnHNpkA&start_radio=1', TRUE, 2),
('Everybody (Backstreet''s Back)', 1997, 'https://www.youtube.com/watch?v=6M6samPEMpM&list=RD6M6samPEMpM&start_radio=1', TRUE, 3);

-- Insert Social Media Links (from footer)
INSERT INTO social_links (platform, url, icon_filename, display_order) VALUES
('Facebook', 'https://www.facebook.com/backstreetboys', 'facebook icon.jpg', 1),
('Instagram', 'https://www.instagram.com/backstreetboys', 'instagram icon.jpg', 2),
('Twitter', 'https://x.com/backstreetboys', 'twitter.jpg', 3),
('YouTube', 'https://www.youtube.com/user/bsbofficial', 'yt icon.jpg', 4),
('Spotify', 'https://open.spotify.com/artist/5rSXSAkZ67PYJSvpUpkOr7?nd=1&dlsi=ef4b4bf4085344ed', 'spotify icon.jpg', 5),
('TikTok', 'https://www.tiktok.com/@backstreetboys', 'tiktok icon.jpg', 6);

-- Create indexes for better performance
CREATE INDEX idx_members_status ON members(status);
CREATE INDEX idx_albums_year ON albums(release_year);
CREATE INDEX idx_timeline_era ON timeline_events(era);
CREATE INDEX idx_songs_hit_rank ON songs(hit_rank);
