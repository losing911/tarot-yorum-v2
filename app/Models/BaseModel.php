<?php
/**
 * Base Model Class
 * Common database operations for all models
 */

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    
    public function __construct(Database $database)
    {
        $this->db = $database;
    }
    
    /**
     * Find record by ID
     */
    public function find($id)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $this->db->bind(':id', $id);
        return $this->db->fetch();
    }
    
    /**
     * Find all records
     */
    public function findAll($limit = null, $offset = 0)
    {
        $sql = "SELECT * FROM {$this->table}";
        
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $this->db->query($sql);
        
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        }
        
        return $this->db->fetchAll();
    }
    
    /**
     * Find records by condition
     */
    public function where($column, $operator, $value)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$column} {$operator} :value");
        $this->db->bind(':value', $value);
        return $this->db->fetchAll();
    }
    
    /**
     * Find single record by condition
     */
    public function findWhere($column, $value)
    {
        $this->db->query("SELECT * FROM {$this->table} WHERE {$column} = :value LIMIT 1");
        $this->db->bind(':value', $value);
        return $this->db->fetch();
    }
    
    /**
     * Create new record
     */
    public function create($data)
    {
        $data = $this->filterFillable($data);
        
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        
        $this->db->execute();
        return $this->db->lastInsertId();
    }
    
    /**
     * Update record by ID
     */
    public function update($id, $data)
    {
        $data = $this->filterFillable($data);
        
        $setPairs = [];
        foreach (array_keys($data) as $column) {
            $setPairs[] = "{$column} = :{$column}";
        }
        
        $sql = "UPDATE {$this->table} SET " . implode(', ', $setPairs) . " WHERE {$this->primaryKey} = :id";
        $this->db->query($sql);
        
        foreach ($data as $key => $value) {
            $this->db->bind(':' . $key, $value);
        }
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Delete record by ID
     */
    public function delete($id)
    {
        $this->db->query("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Count records
     */
    public function count($condition = null)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        
        if ($condition) {
            $sql .= " WHERE {$condition}";
        }
        
        $this->db->query($sql);
        $result = $this->db->fetch();
        return $result['count'];
    }
    
    /**
     * Check if record exists
     */
    public function exists($column, $value)
    {
        $this->db->query("SELECT COUNT(*) as count FROM {$this->table} WHERE {$column} = :value");
        $this->db->bind(':value', $value);
        $result = $this->db->fetch();
        return $result['count'] > 0;
    }
    
    /**
     * Filter data to only fillable fields
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }
    
    /**
     * Execute custom query
     */
    public function query($sql, $params = [])
    {
        $this->db->query($sql);
        
        foreach ($params as $key => $value) {
            $this->db->bind($key, $value);
        }
        
        return $this->db->fetchAll();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->db->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->db->rollback();
    }
}