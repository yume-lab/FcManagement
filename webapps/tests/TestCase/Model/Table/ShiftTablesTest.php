<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ShiftTables;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\ShiftTables Test Case
 */
class ShiftTablesTest extends TestCase
{

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('Shifts') ? [] : ['className' => 'App\Model\Table\ShiftTables'];
        $this->ShiftTables = TableRegistry::get('Shifts', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->ShiftTables);

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
