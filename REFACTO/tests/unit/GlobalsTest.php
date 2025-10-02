<?php
/**
 * Unit Tests for globals.php
 * 
 * Tests the global functions and security features in globals.php
 */

use PHPUnit\Framework\TestCase;

class GlobalsTest extends TestCase
{
    /**
     * Test checkInputArray function with valid input
     */
    public function testCheckInputArrayWithValidInput()
    {
        $testArray = array(
            'valid_key' => 'valid_value',
            'another_key' => 'another_value'
        );
        
        // This should not throw an exception
        checkInputArray($testArray);
        $this->assertTrue(true);
    }
    
    /**
     * Test checkInputArray function with banned keys
     */
    public function testCheckInputArrayWithBannedKeys()
    {
        $this->expectException(Exception::class);
        
        $testArray = array(
            '_get' => 'malicious_value',
            'valid_key' => 'valid_value'
        );
        
        checkInputArray($testArray);
    }
    
    /**
     * Test checkInputArray function with numeric keys
     */
    public function testCheckInputArrayWithNumericKeys()
    {
        $this->expectException(Exception::class);
        
        $testArray = array(
            '0' => 'value',
            'valid_key' => 'valid_value'
        );
        
        checkInputArray($testArray);
    }
    
    /**
     * Test unregisterGlobals function
     */
    public function testUnregisterGlobals()
    {
        // Set up some global variables
        $GLOBALS['test_var'] = 'test_value';
        $GLOBALS['another_var'] = 'another_value';
        
        // Call unregisterGlobals
        unregisterGlobals();
        
        // Check that global variables are cleared
        $this->assertArrayNotHasKey('test_var', $GLOBALS);
        $this->assertArrayNotHasKey('another_var', $GLOBALS);
        
        // Check that superglobals are preserved
        $this->assertArrayHasKey('_GET', $GLOBALS);
        $this->assertArrayHasKey('_POST', $GLOBALS);
        $this->assertArrayHasKey('_COOKIE', $GLOBALS);
        $this->assertArrayHasKey('_SERVER', $GLOBALS);
    }
    
    /**
     * Test registerGlobals function
     */
    public function testRegisterGlobals()
    {
        // Set up some superglobal data
        $_GET['test_param'] = 'test_value';
        $_POST['post_param'] = 'post_value';
        
        // Call registerGlobals
        registerGlobals();
        
        // Check that global variables are created
        $this->assertEquals('test_value', $GLOBALS['test_param']);
        $this->assertEquals('post_value', $GLOBALS['post_param']);
    }
    
    /**
     * Test RG_EMULATION constant
     */
    public function testRGEmulationConstant()
    {
        $this->assertTrue(defined('RG_EMULATION'));
        $this->assertIsInt(RG_EMULATION);
        $this->assertContains(RG_EMULATION, array(0, 1));
    }
    
    /**
     * Test that banned variables are properly defined
     */
    public function testBannedVariablesList()
    {
        // This test ensures the banned variables list is comprehensive
        $expectedBanned = array('_files', '_env', '_get', '_post', '_cookie', '_server', '_session', 'globals');
        
        // We can't directly access the static $banned array, but we can test behavior
        $testCases = array(
            '_files' => 'should_be_banned',
            '_env' => 'should_be_banned',
            '_get' => 'should_be_banned',
            '_post' => 'should_be_banned',
            '_cookie' => 'should_be_banned',
            '_server' => 'should_be_banned',
            '_session' => 'should_be_banned',
            'globals' => 'should_be_banned'
        );
        
        foreach ($testCases as $key => $value) {
            $this->expectException(Exception::class);
            checkInputArray(array($key => $value));
        }
    }
    
    /**
     * Test security against GLOBALS injection
     */
    public function testGlobalsInjectionProtection()
    {
        $maliciousArray = array(
            'GLOBALS' => array('hacked' => 'value'),
            'valid_key' => 'valid_value'
        );
        
        // This should not allow GLOBALS injection
        checkInputArray($maliciousArray);
        $this->assertArrayNotHasKey('hacked', $GLOBALS);
    }
    
    /**
     * Test that valid keys are not blocked
     */
    public function testValidKeysNotBlocked()
    {
        $validKeys = array(
            'user_id',
            'page_title',
            'content_body',
            'admin_settings',
            'site_config'
        );
        
        foreach ($validKeys as $key) {
            $testArray = array($key => 'test_value');
            checkInputArray($testArray);
            $this->assertTrue(true); // If we get here, the key was not blocked
        }
    }
    
    /**
     * Test case sensitivity of banned keys
     */
    public function testBannedKeysCaseSensitivity()
    {
        $caseVariations = array(
            '_GET',
            '_get',
            '_Get',
            '_gEt'
        );
        
        foreach ($caseVariations as $key) {
            $this->expectException(Exception::class);
            checkInputArray(array($key => 'test_value'));
        }
    }
}
