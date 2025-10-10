<?php

declare(strict_types=1);

namespace App\Controller;

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
    public function index()
    {
        $user = $this->Users->newEmptyEntity();
        // $users = $this->paginate($this->Users);
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

                // Custom redirect logic based on user role or other conditions
                if (isset($user['role'])) {
                    switch ($user['role']) {
                        case 'admin':
                            return $this->redirect(['controller' => 'Users', 'action' => 'index']);
                        case 'cashier':
                            return $this->redirect(['controller' => 'sample', 'action' => 'index']);
                        default:
                            return $this->redirect($this->Auth->redirectUrl());
                    }
                }

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



    /*
    // Add this in your AppController or any controller temporarily
    public function initialize(): void
    {
        parent::initialize();

        // Debug database connection
        $connection = \Cake\Datasource\ConnectionManager::get('default');
        $config = $connection->config();
        debug($config); // This will show the actual connection details
        exit;
    }
     */
}
