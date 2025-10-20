<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Http\Exception\UnauthorizedException;

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

    // Add this method to your UsersController (non-API version)
    public function beforeFilter(\Cake\Event\EventInterface $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login', 'logout']);
    }

    public function index()
    {
        $auth = $this->Auth->user();
        $user = $this->Users->newEmptyEntity();

        if ($auth['role'] === 'inspector') {
            return $this->redirect(['controller' => 'Inspections', 'action' => 'index']);
        }
        $this->set(compact('user'));
    }

    public function login()
    {
        $this->viewBuilder()->setLayout('login');
        $user = $this->Users->newEmptyEntity();

        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                return $this->redirect($this->Auth->redirectUrl());
            }
            $this->Flash->error(__('Invalid username or password, try again'));
        }

        $this->set(compact('user'));
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }
}
