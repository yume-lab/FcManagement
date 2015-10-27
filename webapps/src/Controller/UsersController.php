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
            $user = $this->UserAuth->identify();
            if ($user) {
                $this->UserAuth->setUser($user);
                return $this->redirect($this->UserAuth->redirectUrl());
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
        return $this->redirect($this->UserAuth->logout());
    }


    /**
     * マイアカウント編集.
     *
     * @param string|null $id User id.
     * @return void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function account($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success('アカウント情報を更新しました。');
                $this->UserAuth->refresh();
                return $this->redirect('/users/account/'.$id);
            } else {
                $this->Flash->error('アカウント情報の更新に失敗しました。');
            }
        }
        $this->set(compact('user'));
        $this->set('_serialize', ['user']);
        $this->render('edit');
    }
}
