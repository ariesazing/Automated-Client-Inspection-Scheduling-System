<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);

        $auth = $this->Auth->user();

        if ($auth['role'] !== 'admin') {
            $this->Flash->error('Access denied.');
            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
        }
    }
    public function index()
    {

        $users = $this->Users->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($users));
    }

    public function getUsers()
    {
        $users = $this->Users->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $users]));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $result = ['status' => 'success', 'message' => 'The User has been saved.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The User could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $user = $this->Users->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $result = ['status' => 'success', 'message' => 'The User has been saved.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The User could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($user));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $result = ['status' => 'success', 'message' => 'The User has been deleted.'];
        } else {
            $result = ['status' => 'error', 'message' => 'The User could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }

    public function login()
    {
        $this->request->allowMethod(['post']);
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                $data['status'] = 'success';
                $data['user'] = $this->Auth->user();
            } else {
                $data['status'] = 'error';
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($data));
        }
    }

    public function logout()
    {
        $this->Auth->logout();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($data['status'] = 'logout'));
    }
}
