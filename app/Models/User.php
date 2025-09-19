<?php
/**
 * User Model
 * Handle user-related database operations
 */

class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name',
        'birth_date', 'birth_time', 'birth_place', 'zodiac_sign', 
        'avatar', 'role', 'is_active', 'is_email_verified'
    ];
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        return $this->findWhere('email', $email);
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        return $this->findWhere('username', $username);
    }
    
    /**
     * Create new user with hashed password
     */
    public function createUser($data)
    {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        // Determine zodiac sign from birth date
        if (isset($data['birth_date'])) {
            $data['zodiac_sign'] = $this->getZodiacSign($data['birth_date']);
        }
        
        return $this->create($data);
    }
    
    /**
     * Update user password
     */
    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
    
    /**
     * Verify user password
     */
    public function verifyPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }
    
    /**
     * Update last login time
     */
    public function updateLastLogin($userId)
    {
        $this->db->query('UPDATE users SET last_login = CURRENT_TIMESTAMP WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    
    /**
     * Verify user email
     */
    public function verifyEmail($userId)
    {
        $data = [
            'is_email_verified' => 1,
            'email_verified_at' => date('Y-m-d H:i:s')
        ];
        return $this->update($userId, $data);
    }
    
    /**
     * Get user's tarot reading count for today
     */
    public function getTodayTarotReadings($userId)
    {
        $this->db->query(
            'SELECT COUNT(*) as count FROM tarot_readings 
             WHERE user_id = :user_id AND DATE(created_at) = CURDATE()'
        );
        $this->db->bind(':user_id', $userId);
        $result = $this->db->fetch();
        return $result['count'];
    }
    
    /**
     * Get user statistics
     */
    public function getUserStats($userId)
    {
        $stats = [];
        
        // Total blog posts
        $this->db->query(
            'SELECT COUNT(*) as count FROM blog_posts 
             WHERE user_id = :user_id AND status = "published"'
        );
        $this->db->bind(':user_id', $userId);
        $result = $this->db->fetch();
        $stats['blog_posts'] = $result['count'];
        
        // Total tarot readings
        $this->db->query('SELECT COUNT(*) as count FROM tarot_readings WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->fetch();
        $stats['tarot_readings'] = $result['count'];
        
        // Total comments
        $this->db->query(
            'SELECT COUNT(*) as count FROM comments 
             WHERE user_id = :user_id AND status = "approved"'
        );
        $this->db->bind(':user_id', $userId);
        $result = $this->db->fetch();
        $stats['comments'] = $result['count'];
        
        return $stats;
    }
    
    /**
     * Get all users with pagination
     */
    public function getAllWithPagination($page = 1, $perPage = 20, $search = null)
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = 'SELECT * FROM users';
        $params = [];
        
        if ($search) {
            $sql .= ' WHERE username LIKE :search OR email LIKE :search OR first_name LIKE :search OR last_name LIKE :search';
            $params[':search'] = "%{$search}%";
        }
        
        $sql .= ' ORDER BY created_at DESC LIMIT :limit OFFSET :offset';
        
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        $this->db->bind(':limit', $perPage, PDO::PARAM_INT);
        $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        
        return $this->db->fetchAll();
    }
    
    /**
     * Determine zodiac sign from birth date
     */
    private function getZodiacSign($birthDate)
    {
        $date = new DateTime($birthDate);
        $month = (int)$date->format('n');
        $day = (int)$date->format('j');
        
        $signs = [
            'koc' => [['month' => 3, 'day' => 21], ['month' => 4, 'day' => 20]],
            'boga' => [['month' => 4, 'day' => 21], ['month' => 5, 'day' => 21]],
            'ikizler' => [['month' => 5, 'day' => 22], ['month' => 6, 'day' => 21]],
            'yengec' => [['month' => 6, 'day' => 22], ['month' => 7, 'day' => 22]],
            'aslan' => [['month' => 7, 'day' => 23], ['month' => 8, 'day' => 22]],
            'basak' => [['month' => 8, 'day' => 23], ['month' => 9, 'day' => 22]],
            'terazi' => [['month' => 9, 'day' => 23], ['month' => 10, 'day' => 22]],
            'akrep' => [['month' => 10, 'day' => 23], ['month' => 11, 'day' => 21]],
            'yay' => [['month' => 11, 'day' => 22], ['month' => 12, 'day' => 21]],
            'oglak' => [['month' => 12, 'day' => 22], ['month' => 1, 'day' => 20]],
            'kova' => [['month' => 1, 'day' => 21], ['month' => 2, 'day' => 19]],
            'balik' => [['month' => 2, 'day' => 20], ['month' => 3, 'day' => 20]]
        ];
        
        foreach ($signs as $sign => $range) {
            $start = $range[0];
            $end = $range[1];
            
            if ($start['month'] <= $end['month']) {
                // Same year range
                if (($month == $start['month'] && $day >= $start['day']) ||
                    ($month == $end['month'] && $day <= $end['day']) ||
                    ($month > $start['month'] && $month < $end['month'])) {
                    return $sign;
                }
            } else {
                // Year boundary range (e.g., Capricorn)
                if (($month == $start['month'] && $day >= $start['day']) ||
                    ($month == $end['month'] && $day <= $end['day']) ||
                    ($month > $start['month'] || $month < $end['month'])) {
                    return $sign;
                }
            }
        }
        
        return null;
    }
    
    /**
     * Get users for admin panel with filtering
     */
    public function getUsers($limit = 20, $offset = 0, $search = null, $role = null, $status = null)
    {
        try {
            $whereClause = "WHERE deleted_at IS NULL";
            $params = [];
            
            if ($search) {
                $whereClause .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
            
            if ($role) {
                $whereClause .= " AND role = :role";
                $params['role'] = $role;
            }
            
            if ($status) {
                $whereClause .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $stmt = $this->db->prepare("
                SELECT id, username, email, full_name, role, status, created_at, last_login
                FROM users 
                {$whereClause}
                ORDER BY created_at DESC
                LIMIT :limit OFFSET :offset
            ");
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('User getUsers Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get user count for pagination
     */
    public function getUserCount($search = null, $role = null, $status = null)
    {
        try {
            $whereClause = "WHERE deleted_at IS NULL";
            $params = [];
            
            if ($search) {
                $whereClause .= " AND (username LIKE :search OR email LIKE :search OR full_name LIKE :search)";
                $params['search'] = '%' . $search . '%';
            }
            
            if ($role) {
                $whereClause .= " AND role = :role";
                $params['role'] = $role;
            }
            
            if ($status) {
                $whereClause .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM users {$whereClause}");
            
            foreach ($params as $key => $value) {
                $stmt->bindValue(':' . $key, $value);
            }
            $stmt->execute();
            
            return $stmt->fetch()['total'];
            
        } catch (Exception $e) {
            error_log('User getUserCount Error: ' . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get recent users for admin dashboard
     */
    public function getRecentUsers($limit = 5)
    {
        try {
            $stmt = $this->db->prepare("
                SELECT id, username, email, full_name, role, status, created_at
                FROM users 
                WHERE deleted_at IS NULL
                ORDER BY created_at DESC
                LIMIT :limit
            ");
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('User getRecentUsers Error: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Update user for admin panel
     */
    public function updateUserAdmin($userId, $data)
    {
        try {
            $updateFields = [];
            $params = ['id' => $userId];
            
            if (isset($data['role'])) {
                $updateFields[] = "role = :role";
                $params['role'] = $data['role'];
            }
            
            if (isset($data['status'])) {
                $updateFields[] = "status = :status";
                $params['status'] = $data['status'];
            }
            
            if (empty($updateFields)) {
                return false;
            }
            
            $updateFields[] = "updated_at = NOW()";
            
            $stmt = $this->db->prepare("
                UPDATE users 
                SET " . implode(', ', $updateFields) . "
                WHERE id = :id AND deleted_at IS NULL
            ");
            $stmt->execute($params);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log('User updateUserAdmin Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete user (soft delete)
     */
    public function deleteUser($userId)
    {
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET deleted_at = NOW() 
                WHERE id = :id AND deleted_at IS NULL
            ");
            $stmt->execute(['id' => $userId]);
            
            return $stmt->rowCount() > 0;
            
        } catch (Exception $e) {
            error_log('User deleteUser Error: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users for export
     */
    public function getAllUsers()
    {
        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id, username, email, full_name, role, status, 
                    birth_date, zodiac_sign, created_at, last_login
                FROM users 
                WHERE deleted_at IS NULL
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            
            return $stmt->fetchAll();
            
        } catch (Exception $e) {
            error_log('User getAllUsers Error: ' . $e->getMessage());
            return [];
        }
    }
}