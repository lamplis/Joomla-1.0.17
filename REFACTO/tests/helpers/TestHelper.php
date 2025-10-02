<?php
/**
 * Test Helper Class for Joomla 1.0 Testing
 * 
 * Provides common utilities and helper methods for testing
 */

class TestHelper
{
    /**
     * Create a test user object
     * 
     * @param array $attributes User attributes
     * @return stdClass User object
     */
    public static function createTestUser($attributes = array())
    {
        $user = new stdClass();
        $user->id = $attributes['id'] ?? 1;
        $user->username = $attributes['username'] ?? 'testuser';
        $user->name = $attributes['name'] ?? 'Test User';
        $user->email = $attributes['email'] ?? 'test@example.com';
        $user->usertype = $attributes['usertype'] ?? 'Registered';
        $user->gid = $attributes['gid'] ?? 18;
        $user->block = $attributes['block'] ?? 0;
        $user->registerDate = $attributes['registerDate'] ?? date('Y-m-d H:i:s');
        $user->lastvisitDate = $attributes['lastvisitDate'] ?? date('Y-m-d H:i:s');
        $user->activation = $attributes['activation'] ?? '';
        $user->params = $attributes['params'] ?? '';
        
        return $user;
    }
    
    /**
     * Create a test content object
     * 
     * @param array $attributes Content attributes
     * @return stdClass Content object
     */
    public static function createTestContent($attributes = array())
    {
        $content = new stdClass();
        $content->id = $attributes['id'] ?? 1;
        $content->title = $attributes['title'] ?? 'Test Article';
        $content->alias = $attributes['alias'] ?? 'test-article';
        $content->introtext = $attributes['introtext'] ?? 'This is a test article introduction.';
        $content->fulltext = $attributes['fulltext'] ?? 'This is the full text of the test article.';
        $content->state = $attributes['state'] ?? 1;
        $content->sectionid = $attributes['sectionid'] ?? 1;
        $content->catid = $attributes['catid'] ?? 1;
        $content->created = $attributes['created'] ?? date('Y-m-d H:i:s');
        $content->created_by = $attributes['created_by'] ?? 1;
        $content->modified = $attributes['modified'] ?? date('Y-m-d H:i:s');
        $content->modified_by = $attributes['modified_by'] ?? 1;
        $content->publish_up = $attributes['publish_up'] ?? date('Y-m-d H:i:s');
        $content->publish_down = $attributes['publish_down'] ?? '0000-00-00 00:00:00';
        $content->access = $attributes['access'] ?? 0;
        $content->hits = $attributes['hits'] ?? 0;
        $content->ordering = $attributes['ordering'] ?? 0;
        $content->metakey = $attributes['metakey'] ?? '';
        $content->metadesc = $attributes['metadesc'] ?? '';
        
        return $content;
    }
    
    /**
     * Create a test menu object
     * 
     * @param array $attributes Menu attributes
     * @return stdClass Menu object
     */
    public static function createTestMenu($attributes = array())
    {
        $menu = new stdClass();
        $menu->id = $attributes['id'] ?? 1;
        $menu->menutype = $attributes['menutype'] ?? 'mainmenu';
        $menu->name = $attributes['name'] ?? 'Test Menu';
        $menu->alias = $attributes['alias'] ?? 'test-menu';
        $menu->link = $attributes['link'] ?? 'index.php?option=com_content';
        $menu->type = $attributes['type'] ?? 'component';
        $menu->published = $attributes['published'] ?? 1;
        $menu->parent = $attributes['parent'] ?? 0;
        $menu->componentid = $attributes['componentid'] ?? 1;
        $menu->ordering = $attributes['ordering'] ?? 1;
        $menu->access = $attributes['access'] ?? 0;
        $menu->params = $attributes['params'] ?? '';
        
        return $menu;
    }
    
    /**
     * Generate random test data
     * 
     * @param string $type Type of data to generate
     * @param int $length Length of generated data
     * @return string Generated data
     */
    public static function generateRandomData($type = 'string', $length = 10)
    {
        switch ($type) {
            case 'string':
                return substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length);
            case 'email':
                return 'test' . rand(1000, 9999) . '@example.com';
            case 'username':
                return 'testuser' . rand(1000, 9999);
            case 'title':
                return 'Test ' . ucfirst(substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 8));
            default:
                return 'test' . rand(1000, 9999);
        }
    }
    
    /**
     * Clean up test data
     * 
     * @param string $table Table name
     * @param array $conditions WHERE conditions
     */
    public static function cleanupTestData($table, $conditions = array())
    {
        global $database;
        
        if (!$database) {
            return;
        }
        
        $query = "DELETE FROM #__" . $table;
        if (!empty($conditions)) {
            $where = array();
            foreach ($conditions as $field => $value) {
                $where[] = $field . " = '" . $database->getEscaped($value) . "'";
            }
            $query .= " WHERE " . implode(' AND ', $where);
        }
        
        $database->setQuery($query);
        $database->query();
    }
    
    /**
     * Assert that a string contains expected content
     * 
     * @param string $expected Expected content
     * @param string $actual Actual content
     * @param string $message Error message
     */
    public static function assertContains($expected, $actual, $message = '')
    {
        if (strpos($actual, $expected) === false) {
            throw new Exception($message ?: "Expected '$expected' to be contained in '$actual'");
        }
    }
    
    /**
     * Assert that a string does not contain unexpected content
     * 
     * @param string $unexpected Unexpected content
     * @param string $actual Actual content
     * @param string $message Error message
     */
    public static function assertNotContains($unexpected, $actual, $message = '')
    {
        if (strpos($actual, $unexpected) !== false) {
            throw new Exception($message ?: "Expected '$unexpected' not to be contained in '$actual'");
        }
    }
    
    /**
     * Assert that an array has expected keys
     * 
     * @param array $expectedKeys Expected keys
     * @param array $actual Actual array
     * @param string $message Error message
     */
    public static function assertArrayHasKeys($expectedKeys, $actual, $message = '')
    {
        foreach ($expectedKeys as $key) {
            if (!array_key_exists($key, $actual)) {
                throw new Exception($message ?: "Expected array to have key '$key'");
            }
        }
    }
    
    /**
     * Create a temporary file for testing
     * 
     * @param string $content File content
     * @param string $extension File extension
     * @return string File path
     */
    public static function createTempFile($content = '', $extension = 'txt')
    {
        $filename = TEST_TEMP_DIR . '/test_' . uniqid() . '.' . $extension;
        file_put_contents($filename, $content);
        return $filename;
    }
    
    /**
     * Remove temporary file
     * 
     * @param string $filename File path
     */
    public static function removeTempFile($filename)
    {
        if (file_exists($filename)) {
            unlink($filename);
        }
    }
}
