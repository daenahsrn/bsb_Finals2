<?php
/**
 * Album Model - Handles database operations for albums (Movies equivalent)
 */

class Album {
    private $conn;
    private $table = "albums";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all albums with tracks
     */
    public function getAll() {
        $query = "SELECT a.*, 
                  (SELECT COUNT(*) FROM album_tracks WHERE album_id = a.album_id) as track_count
                  FROM " . $this->table . " a 
                  ORDER BY release_year DESC, title ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get single album by ID with tracks
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE album_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Get album tracks
     */
    public function getTracks($albumId) {
        $query = "SELECT * FROM album_tracks WHERE album_id = ? ORDER BY track_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt;
    }

    /**
     * Create new album
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (title, release_date, release_year, cover_image_filename, description, is_featured, is_highlight) 
                  VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['title'],
            $data['release_date'] ?? null,
            $data['release_year'],
            $data['cover_image_filename'] ?? null,
            $data['description'] ?? null,
            $data['is_featured'] ?? false,
            $data['is_highlight'] ?? false
        ]);
    }

    /**
     * Update album
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = ?, release_date = ?, release_year = ?, cover_image_filename = ?, 
                      description = ?, is_featured = ?, is_highlight = ?
                  WHERE album_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['title'],
            $data['release_date'] ?? null,
            $data['release_year'],
            $data['cover_image_filename'] ?? null,
            $data['description'] ?? null,
            $data['is_featured'] ?? false,
            $data['is_highlight'] ?? false,
            $id
        ]);
    }

    /**
     * Delete album
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE album_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Add track to album
     */
    public function addTrack($albumId, $trackData) {
        $query = "INSERT INTO album_tracks (album_id, track_number, title, is_single, youtube_url) 
                  VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $albumId,
            $trackData['track_number'],
            $trackData['title'],
            $trackData['is_single'] ?? false,
            $trackData['youtube_url'] ?? null
        ]);
    }

    /**
     * Update track
     */
    public function updateTrack($trackId, $trackData) {
        $query = "UPDATE album_tracks 
                  SET track_number = ?, title = ?, is_single = ?, youtube_url = ?
                  WHERE track_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $trackData['track_number'],
            $trackData['title'],
            $trackData['is_single'] ?? false,
            $trackData['youtube_url'] ?? null,
            $trackId
        ]);
    }

    /**
     * Delete track
     */
    public function deleteTrack($trackId) {
        $query = "DELETE FROM album_tracks WHERE track_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$trackId]);
    }

    /**
     * Get albums count
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>
