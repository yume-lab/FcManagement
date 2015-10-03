<?php
namespace App\Test\TestCase\Controller;

use App\Controller\IndexController;
use Cake\TestSuite\IntegrationTestCase;

/**
 * App\Controller\IndexController Test Case
 */
class IndexControllerTest extends IntegrationTestCase {

    /**
     * Fixtures
     *
     * @var array
     */
    public $fixtures = [
        'app.index'
    ];

    /**
     * Test index method
     *
     * @return void
     */
    public function testIndex() {
        // セッションが無いのでログイン画面にいく
        $this->get('/');
        $this->assertRedirect('/login');

        $this->session(['User' => ['id' => 'test']]);
        $this->get('/');
        $this->assertRedirect('/dashboard');
    }
}
