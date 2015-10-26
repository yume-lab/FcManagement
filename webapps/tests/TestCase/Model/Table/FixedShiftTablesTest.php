<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\FixedShiftTables;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\FixedShiftTables Test Case
 */
class FixedShiftTablesTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('FixedShifts') ? [] : ['className' => 'App\Model\Table\FixedShiftTables'];
        $this->FixedShiftTables = TableRegistry::get('FixedShifts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->FixedShiftTables);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
