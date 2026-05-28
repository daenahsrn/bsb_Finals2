<?php
/**
 * Member Model - Handles database operations for band members
 * Matches the new database schema with: member_id, about_id, name, stage_name, birthdate, nationality, position, profile_img, bio
 */

class Member {
    private $conn;
    private $table = "members";

    public function __construct($db) {
        $this->conn = $db;
    }

    /**
     * Get all members
     */
    public function getAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    /**
     * Get single member by ID
     */
    public function getById($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE member_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create new member
     */
    public function create($data) {
        $query = "INSERT INTO " . $this->table . " 
                  (about_id, name, stage_name, birthdate, nationality, position, profile_img, bio) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['about_id'] ?? null,
            $data['name'],
            $data['stage_name'] ?? null,
            $data['birthdate'] ?? null,
            $data['nationality'] ?? null,
            $data['position'] ?? 'Vocalist',
            $data['profile_img'] ?? null,
            $data['bio'] ?? null
        ]);
    }

    /**
     * Update member
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET about_id = ?, name = ?, stage_name = ?, birthdate = ?, nationality = ?, 
                      position = ?, profile_img = ?, bio = ?
                  WHERE member_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['about_id'] ?? null,
            $data['name'],
            $data['stage_name'] ?? null,
            $data['birthdate'] ?? null,
            $data['nationality'] ?? null,
            $data['position'] ?? 'Vocalist',
            $data['profile_img'] ?? null,
            $data['bio'] ?? null,
            $id
        ]);
    }

    /**
     * Delete member
     */
    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE member_id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    /**
     * Get members count
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
