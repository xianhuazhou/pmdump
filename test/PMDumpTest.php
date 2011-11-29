<?php
require __DIR__ . '/helper.php';

class PMDumpTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->file = __DIR__ . '/results.sql';
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        $this->pdo = Helper::getPDOConnection();
        Helper::initializeData($this->pdo);
    }

    public function tearDown() 
    {
        Helper::cleanData($this->pdo);
    }

    public function testTables()
    {
        $pmDump = new PMDump($this->pdo);
        $this->assertEquals(array('t1', 't2'), $pmDump->tables(Helper::TEST_DB_NAME));
    }

    public function testDump()
    {
        $pmDump = new PMDump($this->pdo, array('limit_per_select' => 6));
        $pmDump->dump(Helper::TEST_DB_NAME, $this->file);
        $data = file_get_contents($this->file);
        $this->assertTrue('' !== $data);
        $this->assertTrue(false !== strpos($data, 't1'));
        $this->assertTrue(false !== strpos($data, 't2'));
    }
}

