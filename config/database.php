<?php
/**
 * Database Connection Class
 * Secure PDO wrapper with prepared statements
 */

class Database
{
    private $connection;
    private $statement;
    
    public function __construct()
    {
        $this->connect();
    }
    
    /**
     * Create database connection using PDO
     */
    private function connect()
    {
        $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
        
        $options = [
            PDO::ATTR_PERSISTENT => false,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
        ];
        
        try {
            $this->connection = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            error_log('Database Connection Error: ' . $e->getMessage());
            throw new Exception('Database connection failed');
        }
    }
    
    /**
     * Prepare SQL statement
     */
    public function query($sql)
    {
        try {
            $this->statement = $this->connection->prepare($sql);
            return $this;
        } catch (PDOException $e) {
            error_log('Query Prepare Error: ' . $e->getMessage());
            throw new Exception('Query preparation failed');
        }
    }
    
    /**
     * Bind parameter to prepared statement
     */
    public function bind($param, $value, $type = null)
    {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        
        $this->statement->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Execute prepared statement
     */
    public function execute()
    {
        try {
            return $this->statement->execute();
        } catch (PDOException $e) {
            error_log('Query Execution Error: ' . $e->getMessage());
            throw new Exception('Query execution failed');
        }
    }
    
    /**
     * Fetch multiple results
     */
    public function fetchAll()
    {
        $this->execute();
        return $this->statement->fetchAll();
    }
    
    /**
     * Fetch single result
     */
    public function fetch()
    {
        $this->execute();
        return $this->statement->fetch();
    }
    
    /**
     * Get row count
     */
    public function rowCount()
    {
        return $this->statement->rowCount();
    }
    
    /**
     * Get last inserted ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
    
    /**
     * Begin transaction
     */
    public function beginTransaction()
    {
        return $this->connection->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->connection->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->connection->rollback();
    }
    
    /**
     * Check if table exists
     */
    public function tableExists($table)
    {
        $sql = "SHOW TABLES LIKE :table";
        $this->query($sql)->bind(':table', $table);
        $result = $this->fetch();
        return !empty($result);
    }
    
    /**
     * Execute raw SQL (for migrations only)
     */
    public function exec($sql)
    {
        try {
            return $this->connection->exec($sql);
        } catch (PDOException $e) {
            error_log('Raw SQL Execution Error: ' . $e->getMessage());
            throw new Exception('Raw SQL execution failed: ' . $e->getMessage());
        }
    }
    
    /**
     * Get PDO connection (use with caution)
     */
    public function getConnection()
    {
        return $this->connection;
    }
    
    /**
     * Prepare statement directly (for compatibility)
     */
    public function prepare($sql)
    {
        try {
            return $this->connection->prepare($sql);
        } catch (PDOException $e) {
            error_log('Prepare Error: ' . $e->getMessage());
            throw new Exception('Statement preparation failed');
        }
    }
}