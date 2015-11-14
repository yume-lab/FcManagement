<?php
namespace App\Test\TestCase\Model\Table;

use App\Model\Table\LatestTimeCardsTable;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;

/**
 * App\Model\Table\LatestTimeCardsTable Test Case
 */
class LatestTimeCardsTableTest extends TestCase
{

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.latest_time_cards',
        'app.stores',
        'app.store_categories',
        'app.employees',
        'app.roles',
        'app.user_stores',
        'app.users',
        'app.time_card_states'
    ];

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        $config = TableRegistry::exists('LatestTimeCards') ? [] : ['className' => 'App\Model\Table\LatestTimeCardsTable'];
        $this->LatestTimeCards = TableRegistry::get('LatestTimeCards', $config);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown()
    {
        unset($this->LatestTimeCards);

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

    /**
     * Test buildRules method
     *
     * @return void
     */
    public function testBuildRules()
    {
        $this->markTestIncomplete('Not implemented yet.');
    }
}
