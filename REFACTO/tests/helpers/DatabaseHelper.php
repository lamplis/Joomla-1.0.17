<?php
/**
 * Database Helper Class for Joomla 1.0 Testing
 * 
 * Provides database utilities and helper methods for testing
 */

class DatabaseHelper
{
    /**
     * Get database connection
     * 
     * @return database Database connection
     */
    public static function getDatabase()
    {
        global $database;
        return $database;
    }
    
    /**
     * Execute a query and return results
     * 
     * @param string $query SQL query
     * @return array Query results
     */
    public static function executeQuery($query)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $database->setQuery($query);
        return $database->loadObjectList();
    }
    
    /**
     * Execute a query and return single result
     * 
     * @param string $query SQL query
     * @return object Single query result
     */
    public static function executeQuerySingle($query)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $database->setQuery($query);
        return $database->loadObject();
    }
    
    /**
     * Insert test data into a table
     * 
     * @param string $table Table name (without prefix)
     * @param array $data Data to insert
     * @return int Insert ID
     */
    public static function insertTestData($table, $data)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $fields = array_keys($data);
        $values = array_values($data);
        
        // Escape values
        $escapedValues = array();
        foreach ($values as $value) {
            $escapedValues[] = "'" . $database->getEscaped($value) . "'";
        }
        
        $query = "INSERT INTO #__" . $table . " (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $escapedValues) . ")";
        $database->setQuery($query);
        $database->query();
        
        return $database->insertid();
    }
    
    /**
     * Update test data in a table
     * 
     * @param string $table Table name (without prefix)
     * @param array $data Data to update
     * @param array $conditions WHERE conditions
     * @return bool Success status
     */
    public static function updateTestData($table, $data, $conditions)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $setParts = array();
        foreach ($data as $field => $value) {
            $setParts[] = $field . " = '" . $database->getEscaped($value) . "'";
        }
        
        $whereParts = array();
        foreach ($conditions as $field => $value) {
            $whereParts[] = $field . " = '" . $database->getEscaped($value) . "'";
        }
        
        $query = "UPDATE #__" . $table . " SET " . implode(', ', $setParts) . " WHERE " . implode(' AND ', $whereParts);
        $database->setQuery($query);
        
        return $database->query();
    }
    
    /**
     * Delete test data from a table
     * 
     * @param string $table Table name (without prefix)
     * @param array $conditions WHERE conditions
     * @return bool Success status
     */
    public static function deleteTestData($table, $conditions)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $whereParts = array();
        foreach ($conditions as $field => $value) {
            $whereParts[] = $field . " = '" . $database->getEscaped($value) . "'";
        }
        
        $query = "DELETE FROM #__" . $table . " WHERE " . implode(' AND ', $whereParts);
        $database->setQuery($query);
        
        return $database->query();
    }
    
    /**
     * Get test user by username
     * 
     * @param string $username Username
     * @return object User object
     */
    public static function getTestUser($username)
    {
        $query = "SELECT * FROM #__users WHERE username = '" . self::getDatabase()->getEscaped($username) . "'";
        return self::executeQuerySingle($query);
    }
    
    /**
     * Get test content by title
     * 
     * @param string $title Content title
     * @return object Content object
     */
    public static function getTestContent($title)
    {
        $query = "SELECT * FROM #__content WHERE title = '" . self::getDatabase()->getEscaped($title) . "'";
        return self::executeQuerySingle($query);
    }
    
    /**
     * Get test menu by name
     * 
     * @param string $name Menu name
     * @return object Menu object
     */
    public static function getTestMenu($name)
    {
        $query = "SELECT * FROM #__menu WHERE name = '" . self::getDatabase()->getEscaped($name) . "'";
        return self::executeQuerySingle($query);
    }
    
    /**
     * Count records in a table
     * 
     * @param string $table Table name (without prefix)
     * @param array $conditions WHERE conditions
     * @return int Record count
     */
    public static function countRecords($table, $conditions = array())
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $query = "SELECT COUNT(*) FROM #__" . $table;
        
        if (!empty($conditions)) {
            $whereParts = array();
            foreach ($conditions as $field => $value) {
                $whereParts[] = $field . " = '" . $database->getEscaped($value) . "'";
            }
            $query .= " WHERE " . implode(' AND ', $whereParts);
        }
        
        $database->setQuery($query);
        return (int) $database->loadResult();
    }
    
    /**
     * Check if a table exists
     * 
     * @param string $table Table name (without prefix)
     * @return bool Table exists
     */
    public static function tableExists($table)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $query = "SHOW TABLES LIKE '#__" . $table . "'";
        $database->setQuery($query);
        $result = $database->loadResult();
        
        return !empty($result);
    }
    
    /**
     * Get table structure
     * 
     * @param string $table Table name (without prefix)
     * @return array Table structure
     */
    public static function getTableStructure($table)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $query = "DESCRIBE #__" . $table;
        $database->setQuery($query);
        return $database->loadObjectList();
    }
    
    /**
     * Truncate a table
     * 
     * @param string $table Table name (without prefix)
     * @return bool Success status
     */
    public static function truncateTable($table)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $query = "TRUNCATE TABLE #__" . $table;
        $database->setQuery($query);
        
        return $database->query();
    }
    
    /**
     * Reset auto increment for a table
     * 
     * @param string $table Table name (without prefix)
     * @param int $startValue Starting value
     * @return bool Success status
     */
    public static function resetAutoIncrement($table, $startValue = 1)
    {
        $database = self::getDatabase();
        if (!$database) {
            throw new Exception('Database connection not available');
        }
        
        $query = "ALTER TABLE #__" . $table . " AUTO_INCREMENT = " . (int) $startValue;
        $database->setQuery($query);
        
        return $database->query();
    }
}
