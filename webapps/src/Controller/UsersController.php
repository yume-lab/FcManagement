<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;

/**
 * Users Controller
 * システム利用者のCRUDコントローラー
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{

    /**
     * リクエスト毎の処理.
     * ユーザー追加については別の認証をかける.
     *
     * @param \Cake\Event\Event $event
     */
    public function beforeFilter(Event $event) {
        parent::beforeFilter($event);

        $actions = ['add'];
        $action = $this->request->param('action');

        if (!in_array($action, $actions)) {
            return;
        }
        $this->Auth->allow($actions);

        // TODO: Basic認証のユーザーとパスワードを決める
        $username = "admin";
        $password = "password";

        if (!isset($_SERVER['PHP_AUTH_USER'])) {
            $this->authByBasic();
        } else {
            if ($_SERVER['PHP_AUTH_USER'] !== $username || $_SERVER['PHP_AUTH_PW'] !== $password) {
                $this->authByBasic();
            }
        }
    }

    /**
     * 一覧表示.
     *
     * @return void
     */
    public function index()
    {
        $this->set('users', $this->paginate($this->Users));
        $this->set('_serialize', ['users']);
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return void
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function view($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        $this->set('user', $user);
        $this->set('_serialize', ['user']);
    }

    /**
     * ユーザーの追加を行います.
     *
     * @return void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        parent::removeViewFrame();
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success('登録しました');
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error('登録に失敗しました');
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('The user has been saved.'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The user could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return void Redirects to index.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success(__('The user has been deleted.'));
        } else {
            $this->Flash->error(__('The user could not be deleted. Please, try again.'));
        }
        return $this->redirect(['action' => 'index']);
    }

    /**
     * ログイン画面表示、またはログイン処理を行います.
     * POST通信の場合のみ、ログイン処理を実行します.
     *
     * @return \Cake\Network\Response|void
     */
    public function login()
    {
        parent::removeViewFrame();
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error('メールアドレス、またはパスワードが正しくありません。');
            }
        }
    }

    /**
     * ログアウト処理を実施します.
     * @return \Cake\Network\Response|void
     */
    public function logout()
    {
        $this->Flash->success('ログアウトしました。');
        return $this->redirect($this->Auth->logout());
    }


    /**
     * Basic認証を行います.
     */
    protected function authByBasic()
    {
        header('WWW-Authenticate: Basic realm="Please enter your ID and password"');
        header('HTTP/1.0 401 Unauthorized');
        die("Authorization Required");
    }

}
