<?php
/**
 * Database Migration Runner
 * Run this file to create all database tables
 */

// Include the necessary files
require_once '../config/config.php';
require_once '../config/database.php';
require_once 'Migration.php';

try {
    echo "Starting database migration...\n";
    
    // Create database connection
    $database = new Database();
    
    // Run migrations
    $migration = new Migration($database);
    $migration->runMigrations();
    
    echo "Database migration completed successfully!\n";
    echo "Default admin user created:\n";
    echo "Email: admin@tarot-yorum.fun\n";
    echo "Password: admin123\n";
    echo "Please change the admin password after first login!\n";
    
} catch (Exception $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
    exit(1);
}