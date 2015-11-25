<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TimeCardStatesTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\TimeCardStatesTable Test Case
 */
class TimeCardStatesTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.time_card_states',
        'app.latest_time_cards',
        'app.stores',
        'app.store_categories',
        'app.employees',
        'app.roles',
        'app.user_stores',
        'app.users',
        'app.employee_salaries'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('TimeCardStates') ? [] : ['className' => 'App\Model\Table\TimeCardStatesTable'];
        $this->TimeCardStates = TableRegistry::get('TimeCardStates', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->TimeCardStates);

        parent::tearDown();
    }

    /**
     * Test initialize method
     *
     * @return void
     */
    public function testInitialize()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
