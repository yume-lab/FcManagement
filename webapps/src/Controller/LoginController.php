<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * ログイン画面コントローラー.
 */
class LoginController extends AppController {

    /**
     * ログイン画面の表示
     *
     * @return void
     */
    public function index() {
        $this->viewBuilder()->layout('nowrap');
    }

    /**
     * 認証処理
     */
    public function auth() {

        return $this->redirect('/dashboard');
    }

}
