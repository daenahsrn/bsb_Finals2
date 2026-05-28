<?php
/**
 * Album Model - Handles database operations for albums
 * Matches the new database schema with: album_id, title, release, cover_img, description
 */

class Album {
    private $conn;
    private $table = "albums";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all albums
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY release DESC, title ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get single album by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE album_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new album
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (title, release, cover_img, description) 
                  VALUES (?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['title'],
            $data['release'] ?? null,
            $data['cover_img'] ?? null,
            $data['description'] ?? null
        ]);
    }

    /**
     * Update album
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET title = ?, release = ?, cover_img = ?, description = ?
                  WHERE album_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['title'],
            $data['release'] ?? null,
            $data['cover_img'] ?? null,
            $data['description'] ?? null,
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
