<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * SchedulingLogs Controller
 *
 * @property \App\Model\Table\SchedulingLogsTable $SchedulingLogs
 * @method \App\Model\Entity\SchedulingLog[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SchedulingLogsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Inspections'],
        ];
        $schedulingLogs = $this->paginate($this->SchedulingLogs);

        $this->set(compact('schedulingLogs'));
    }   

    /**
     * View method
     *
     * @param string|null $id Scheduling Log id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $schedulingLog = $this->SchedulingLogs->get($id, [
            'contain' => ['Inspections'],
        ]);

        $this->set(compact('schedulingLog'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function getSchedulingLogs()
    {
        $schedulingLogs = $this->SchedulingLogs->find()
            ->contain(['Users']);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $schedulingLogs]));
    }
    
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $schedulingLog = $this->SchedulingLogs->get($id);
        if ($this->SchedulingLogs->delete($schedulingLog)) {
            $this->Flash->success(__('The scheduling log has been deleted.'));
        } else {
            $this->Flash->error(__('The scheduling log could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
