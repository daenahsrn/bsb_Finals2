<?php
/**
 * Track Model - Handles database operations for album tracks/songs
 */

class Track {
    private $conn;
    private $table = "album_tracks";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all tracks with album info
     */
    public function getAll() {
        $query = "SELECT t.*, a.title as album_title 
                  FROM " . $this->table . " t 
                  LEFT JOIN albums a ON t.album_id = a.album_id
                  ORDER BY a.release_year DESC, t.track_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get tracks by album ID
     */
    public function getByAlbumId($albumId) {
        $query = "SELECT * FROM " . $this->table . " WHERE album_id = ? ORDER BY track_number ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$albumId]);
        return $stmt;
    }

    /**
     * Get single track by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE track_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new track
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (album_id, track_number, title, duration, is_single, youtube_url) 
                  VALUES (?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['album_id'],
            $data['track_number'],
            $data['title'],
            $data['duration'] ?? null,
            $data['is_single'] ?? false,
            $data['youtube_url'] ?? null
        ]);
    }

    /**
     * Update track
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET album_id = ?, track_number = ?, title = ?, duration = ?, 
                      is_single = ?, youtube_url = ?
                  WHERE track_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['album_id'],
            $data['track_number'],
            $data['title'],
            $data['duration'] ?? null,
            $data['is_single'] ?? false,
            $data['youtube_url'] ?? null,
            $id
        ]);
    }

    /**
     * Delete track
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE track_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Get tracks count
     */
    public function getCount() {
        $query = "SELECT COUNT(*) as count FROM " . $this->table;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch();
        return $result['count'];
    }

    /**
     * Get tracks count by album
     */
    public function getCountByAlbum($albumId) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE album_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$albumId]);
        $result = $stmt->fetch();
        return $result['count'];
    }
}
?>
