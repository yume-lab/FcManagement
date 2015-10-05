<?php
namespace App\Controller;

use App\Controller\AppController;

/**
 * 初期表示Controller
 */
class IndexController extends AppController {

    /**
     * 初期表示処理を行います.
     *
     * ログイン済の場合 -> ダッシュボード画面に遷移
     * 未ログインの場合 -> ログイン画面に遷移します
     *
     * @return void|\Cake\Network\Response
     */
    public function index() {

        $hasLogin = !empty(parent::getSession()->read('User'));
        if ($hasLogin) {
            return $this->redirect('/dashboard');
        }
        return $this->redirect('/login');
    }

}
