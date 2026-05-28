# Backstreet Boys Fan Website - Database & Backend Setup Guide

## Overview
This project converts the static HTML Backstreet Boys fan website into a dynamic PHP application with MySQL database backend. The system includes CRUD (Create, Read, Update, Delete) functionality for managing band members (characters) and albums (movies).

## Files Created

### Database
- `database_schema.sql` - Complete MySQL database structure with tables and sample data

### Backend (PHP)
- `config/database.php` - Database connection configuration
- `models/Member.php` - Member model for CRUD operations
- `models/Album.php` - Album model for CRUD operations
- `api/members.php` - REST API for members
- `api/albums.php` - REST API for albums
- `dashboard.php` - Admin dashboard with database integration

## Database Tables

### 1. members
Stores band member information (Characters)
- member_id, member_number, full_name, stage_name
- birth_date, birth_place, role, description
- image_filename, is_founding_member, joined_year, status

### 2. albums
Stores album information (Movies equivalent)
- album_id, title, release_date, release_year
- cover_image_filename, description
- is_featured, is_highlight, sales_count

### 3. album_tracks
Stores track listings for each album
- track_id, album_id, track_number, title
- duration, is_single, youtube_url

### 4. timeline_events
Stores band history timeline events
- event_id, event_year, title, description
- era, is_highlight, position

### 5. songs
Stores top hit songs
- song_id, title, release_year, album_id
- youtube_url, is_hit, hit_rank, play_count

### 6. users
Stores user account information
- user_id, username, email, password_hash
- full_name, favorite_member_id, status, user_type

### 7. social_links
Stores social media links from footer
- social_id, platform, url, icon_filename
- display_order, is_active

## Setup Instructions

### Step 1: Install XAMPP/WAMP/MAMP
Ensure you have a local server environment with:
- PHP 7.4 or higher
- MySQL 5.7 or higher
- phpMyAdmin (optional but recommended)

### Step 2: Create Database
1. Open phpMyAdmin (http://localhost/phpmyadmin)
2. Create a new database named `bsb_fan_website`
3. Select the database
4. Import `database_schema.sql` file
   - Click "Import" tab
   - Choose the SQL file
   - Click "Go"

OR run via command line:
```bash
mysql -u root -p bsb_fan_website < database_schema.sql
```

### Step 3: Configure Database Connection
Edit `config/database.php` if needed:
```php
private $host = "localhost";
private $db_name = "bsb_fan_website";
private $username = "root";
private $password = ""; // Your MySQL password
```

### Step 4: Copy Files to Web Server
Copy all files to your web server directory:
- XAMPP: `C:/xampp/htdocs/Website_BB/`
- WAMP: `C:/wamp64/www/Website_BB/`
- MAMP: `/Applications/MAMP/htdocs/Website_BB/`

### Step 5: Access the Application
1. Start Apache and MySQL in XAMPP/WAMP control panel
2. Access the admin dashboard: http://localhost/Website_BB/dashboard.php
3. View the website: http://localhost/Website_BB/home.php

## Features

### Admin Dashboard (`dashboard.php`)
- **Members Management (Characters)**
  - View all band members in a table
  - Add new members
  - Edit existing member information
  - Delete members
  - Real-time statistics

- **Albums Management (Movies)**
  - View all albums in a grid layout
  - Track count per album
  - Add new albums
  - Edit album details
  - Delete albums (cascades to tracks)
  - Featured/Highlight indicators

### API Endpoints

#### Members API (`api/members.php`)
- `GET /api/members.php` - Get all members
- `GET /api/members.php?action=getById&id=1` - Get single member
- `POST /api/members.php` - Create new member
- `PUT /api/members.php?id=1` - Update member
- `DELETE /api/members.php?id=1` - Delete member

#### Albums API (`api/albums.php`)
- `GET /api/albums.php` - Get all albums
- `GET /api/albums.php?action=getById&id=1` - Get album with tracks
- `GET /api/albums.php?action=getTracks&album_id=1` - Get album tracks
- `POST /api/albums.php` - Create new album
- `POST /api/albums.php?action=addTrack&album_id=1` - Add track to album
- `PUT /api/albums.php?id=1` - Update album
- `DELETE /api/albums.php?id=1` - Delete album
- `DELETE /api/albums.php?id=1&action=deleteTrack&track_id=1` - Delete track

### Sample Data Included
The database schema includes pre-populated data:
- 5 band members (AJ, Nick, Kevin, Brian, Howie)
- 5 albums with complete track listings
- 16 timeline events from 1993-2025
- 3 top hit songs
- 6 social media links

## Converting HTML Pages to PHP

To make your existing HTML pages dynamic, convert them to PHP:

### Example: about.php
```php
<?php
include_once 'config/database.php';
include_once 'models/Member.php';

$database = new Database();
$db = $database->getConnection();
$member = new Member($db);

$membersStmt = $member->getAll();
$members = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Backstreet Boys - Meet the Legends</title>
    <!-- ... rest of head ... -->
</head>
<body>
    <!-- ... header ... -->
    
    <section class="band_Members">
        <?php foreach ($members as $member): ?>
        <div class="band_card">
            <img src="image/<?php echo htmlspecialchars($member['image_filename']); ?>" 
                 alt="<?php echo htmlspecialchars($member['full_name']); ?>">
            <h3><?php echo htmlspecialchars($member['full_name']); ?></h3>
            <h5><?php echo htmlspecialchars($member['stage_name'] ?? ''); ?></h5>
            <p><?php echo htmlspecialchars($member['description']); ?></p>
        </div>
        <?php endforeach; ?>
    </section>
    
    <!-- ... footer ... -->
</body>
</html>
```

### Example: topSongs.php
```php
<?php
include_once 'config/database.php';
include_once 'models/Album.php';

$database = new Database();
$db = $database->getConnection();
$album = new Album($db);

$albumsStmt = $album->getAll();
$albums = $albumsStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!-- ... header ... -->

<section class="container">
    <?php foreach ($albums as $album): ?>
    <div class="album_card <?php echo $album['is_highlight'] ? 'highlight-album' : ''; ?>">
        <div class="album-year-badge"><?php echo $album['release_year']; ?></div>
        <img src="image/<?php echo htmlspecialchars($album['cover_image_filename']); ?>" 
             alt="<?php echo htmlspecialchars($album['title']); ?>">
        <h3><?php echo htmlspecialchars($album['title']); ?></h3>
        <h5 class="release_date">Released: <?php echo date('F d, Y', strtotime($album['release_date'])); ?></h5>
        <div class="tracklist">
            <?php
            $tracks = $album->getTracks($album['album_id']);
            foreach ($tracks as $track):
            ?>
            <p><?php echo htmlspecialchars($track['title']); ?></p>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</section>

<!-- ... footer ... -->
```

## Display Button Integration

The dashboard includes a "View Website" button that links to `home.php`. To make this work:

1. Convert your `home.html` to `home.php`
2. Update navigation links in all pages to use `.php` extension
3. The dashboard will automatically display data from the database

## Troubleshooting

### Database Connection Error
- Check MySQL service is running
- Verify database name, username, password in `config/database.php`
- Ensure database exists and tables are created

### API Returns Empty Data
- Check file permissions
- Verify database has data
- Check error logs in browser console

### Images Not Loading
- Ensure `image/` folder exists
- Verify image filenames match database records
- Check file paths are correct

## Next Steps

1. Convert remaining HTML pages to PHP:
   - `home.html` → `home.php`
   - `about.html` → `about.php`
   - `topSongs.html` → `topSongs.php`
   - `history.html` → `history.php`

2. Add authentication for admin dashboard
3. Implement file upload for images
4. Add search and filter functionality
5. Create user registration/login system

## Support

For issues or questions, check:
- PHP error logs
- Browser console for JavaScript errors
- MySQL error logs
- Network tab in browser DevTools for API calls
