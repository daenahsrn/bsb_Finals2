-- Database Schema for Backstreet Boys Fan Website
-- Based on specific user requirements

CREATE DATABASE IF NOT EXISTS bsb_fan_website;
USE bsb_fan_website;

-- Drop tables if they exist (in correct order due to foreign keys)
DROP TABLE IF EXISTS songs;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS albums;
DROP TABLE IF EXISTS history;
DROP TABLE IF EXISTS about;
DROP TABLE IF EXISTS admins;

-- 1. Admins Table (Replaces Users)
CREATE TABLE admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL,
    created DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Insert default admin (password: 'password')
-- Hash generated using PHP password_hash('password', PASSWORD_DEFAULT)
INSERT INTO admins (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- 2. About Table (For About Page Sections)
CREATE TABLE about (
    about_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(25) NOT NULL,
    content TEXT NOT NULL,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- 3. Members Table (Linked to About)
CREATE TABLE members (
    member_id INT AUTO_INCREMENT PRIMARY KEY,
    about_id INT,
    name VARCHAR(75) NOT NULL,
    stage_name VARCHAR(75),
    birthdate DATE,
    nationality VARCHAR(75),
    position VARCHAR(100), -- Vocalist, Rapper, Dancer, etc.
    profile_img VARCHAR(100), -- Image path
    bio TEXT,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (about_id) REFERENCES about(about_id) ON DELETE SET NULL
);

-- 4. Albums Table
CREATE TABLE albums (
    album_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(100) NOT NULL,
    release DATE,
    cover_img VARCHAR(100), -- Image path
    description TEXT
);

-- 5. Songs Table (Linked to Albums)
CREATE TABLE songs (
    song_id INT AUTO_INCREMENT PRIMARY KEY,
    album_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    duration TIME,
    track_no INT,
    lyrics TEXT,
    audio_file VARCHAR(100), -- Audio path/Link
    FOREIGN KEY (album_id) REFERENCES albums(album_id) ON DELETE CASCADE
);

-- 6. History/Timeline Table
CREATE TABLE history (
    timeline_id INT AUTO_INCREMENT PRIMARY KEY,
    year DATE, -- Storing as Date to allow specific dates if needed, or just Year-01-01
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    last_updated DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- SEED DATA (Based on previous context)

-- About Sections
INSERT INTO about (title, content) VALUES 
('The Group', 'The Backstreet Boys are an American vocal group consisting of cousins Kevin Richardson and Brian Littrell, along with Nick Carter, Howie Dorough, and AJ McLean. Formed in 1993, they became one of the best-selling boy bands of all time.'),
('Legacy', 'With over 100 million records sold worldwide, they have defined a generation of pop music. Their harmonies and choreography set the standard for boy bands in the late 90s and early 2000s.');

-- Members (Linking to the first 'The Group' about_id = 1)
INSERT INTO members (about_id, name, stage_name, birthdate, nationality, position, profile_img, bio) VALUES 
(1, 'Brian Thomas Littrell', 'Brian', '1975-02-20', 'American', 'Lead Vocals', 'images/brian.jpg', 'Known for his high tenor voice and heartfelt ballads.'),
(1, 'Kevin Scott Richardson', 'Kevin', '1971-10-03', 'American', 'Vocals, Bass', 'images/kevin.jpg', 'The oldest member, known for his deep baritone voice.'),
(1, 'Alexander James McLean', 'AJ', '1978-01-09', 'American', 'Vocals, Dancer', 'images/aj.jpg', 'Known for his distinct voice and flashy dance moves.'),
(1, 'Howard Dwaine Dorough', 'Howie D', '1973-08-22', 'American', 'Vocals', 'images/howie.jpg', 'Brings a unique tenor sound and strong work ethic.'),
(1, 'Nicholas Gene Carter', 'Nick', '1980-01-28', 'American', 'Vocals, Dancer', 'images/nick.jpg', 'The youngest member, known for his blonde hair and pop appeal.');

-- Albums
INSERT INTO albums (title, release, cover_img, description) VALUES 
('Backstreet Boys', '1996-05-06', 'images/bsb_debut.jpg', 'Their international debut album.'),
('Backstreet\'s Back', '1997-08-11', 'images/bsb_back.jpg', 'The album that launched them to global superstardom.'),
('Millennium', '1999-05-18', 'images/millennium.jpg', 'Broke first-week sales records in the US.'),
('Black & Blue', '2000-11-21', 'images/black_blue.jpg', 'Featured a darker, more mature sound.'),
('Never Gone', '2005-06-14', 'images/never_gone.jpg', 'Marked their return after a hiatus.');

-- Songs (Linked to Albums)
-- Album 1: Backstreet Boys
INSERT INTO songs (album_id, title, duration, track_no, lyrics, audio_file) VALUES 
(1, 'We\'ve Got It Goin\' On', '03:45', 1, 'Lyrics here...', 'audio/bsb1_01.mp3'),
(1, 'Anywhere For You', '04:20', 2, 'Lyrics here...', 'audio/bsb1_02.mp3'),
(1, 'I\'ll Never Break Your Heart', '04:25', 3, 'Lyrics here...', 'audio/bsb1_03.mp3');

-- Album 2: Backstreet's Back
INSERT INTO songs (album_id, title, duration, track_no, lyrics, audio_file) VALUES 
(2, 'Everybody (Backstreet\'s Back)', '03:45', 1, 'Lyrics here...', 'audio/bsb2_01.mp3'),
(2, 'As Long As You Love Me', '03:30', 2, 'Lyrics here...', 'audio/bsb2_02.mp3'),
(2, 'All I Have To Give', '03:50', 3, 'Lyrics here...', 'audio/bsb2_03.mp3');

-- Album 3: Millennium
INSERT INTO songs (album_id, title, duration, track_no, lyrics, audio_file) VALUES 
(3, 'Larger Than Life', '03:55', 1, 'Lyrics here...', 'audio/mil_01.mp3'),
(3, 'I Want It That Way', '03:35', 2, 'Lyrics here...', 'audio/mil_02.mp3'),
(3, 'Show Me The Meaning Of Being Lonely', '03:50', 3, 'Lyrics here...', 'audio/mil_03.mp3');

-- History Timeline
-- History Timeline (with title field)
DELETE FROM history;
INSERT INTO history (year, title, description) VALUES
('1993-04-20', 'The Beginning', 'The group is formed in Orlando, Florida.'),
('1996-05-06', 'International Debut', 'Debut album released internationally.'),
('1997-08-11', 'Backstreet''s Back', 'Released "Backstreet''s Back", breaking them in the US.'),
('1999-05-18', 'Millennium Era', '"Millennium" released, selling 1.13 million copies in week one.'),
('2001-02-05', 'Kevin Leaves', 'Kevin Richardson leaves the group.'),
('2012-04-29', 'Kevin Returns', 'Kevin Richardson officially rejoins the group.');
