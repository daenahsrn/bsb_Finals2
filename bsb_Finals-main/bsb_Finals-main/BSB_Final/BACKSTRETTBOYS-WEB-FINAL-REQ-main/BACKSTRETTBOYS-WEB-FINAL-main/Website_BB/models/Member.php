<?php
/**
 * Member Model - Handles database operations for band members (Characters)
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
        $query = "SELECT * FROM " . $this->table . " ORDER BY member_number ASC";
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
                  (member_number, full_name, stage_name, birth_date, birth_place, role, description, image_filename, is_founding_member, joined_year, status) 
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['member_number'],
            $data['full_name'],
            $data['stage_name'] ?? null,
            $data['birth_date'] ?? null,
            $data['birth_place'] ?? null,
            $data['role'] ?? 'Vocalist',
            $data['description'] ?? null,
            $data['image_filename'] ?? null,
            $data['is_founding_member'] ?? false,
            $data['joined_year'] ?? date('Y'),
            $data['status'] ?? 'active'
        ]);
    }

    /**
     * Update member
     */
    public function update($id, $data) {
        $query = "UPDATE " . $this->table . " 
                  SET member_number = ?, full_name = ?, stage_name = ?, birth_date = ?, birth_place = ?, 
                      role = ?, description = ?, image_filename = ?, is_founding_member = ?, joined_year = ?, status = ?
                  WHERE member_id = ?";
        
        $stmt = $this->conn->prepare($query);
        
        return $stmt->execute([
            $data['member_number'],
            $data['full_name'],
            $data['stage_name'] ?? null,
            $data['birth_date'] ?? null,
            $data['birth_place'] ?? null,
            $data['role'] ?? 'Vocalist',
            $data['description'] ?? null,
            $data['image_filename'] ?? null,
            $data['is_founding_member'] ?? false,
            $data['joined_year'] ?? date('Y'),
            $data['status'] ?? 'active',
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
