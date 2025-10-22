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
        $this->autoRender = false;
        $this->response = $this->response->withType('application/json');

        try {
            $schedulingLogs = $this->SchedulingLogs->find()
                ->contain([
                    'Users',
                    'Inspections' => [
                        'Clients' // Include Clients to get establishment_name
                    ]
                ])
                ->order(['SchedulingLogs.created_at' => 'DESC'])
                ->all();

            $formattedLogs = [];
            foreach ($schedulingLogs as $log) {
                $formattedLogs[] = [
                    'id' => $log->id,
                    'inspection_id' => $log->inspection_id,
                    'old_date' => $log->old_date ? $log->old_date->format('Y-m-d') : null,
                    'new_date' => $log->new_date ? $log->new_date->format('Y-m-d') : null,
                    'reason' => $log->reason,
                    'updated_by' => $log->updated_by,
                    'created_at' => $log->created_at ? $log->created_at->format('Y-m-d H:i:s') : null,
                    'user' => $log->user ? [
                        'username' => $log->user->username,
                        'name' => $log->user->username
                    ] : null,
                    'inspection' => $log->inspection ? [
                        'client' => $log->inspection->client ? [
                            'establishment_name' => $log->inspection->client->establishment_name
                        ] : null,
                        'client_name' => $log->inspection->client->establishment_name ?? null
                    ] : null,
                    'establishment_name' => $log->inspection->client->establishment_name ?? 'Unknown Establishment'
                ];
            }

            $this->response = $this->response->withStringBody(json_encode($formattedLogs));
            return $this->response;
        } catch (\Exception $e) {
            $this->response = $this->response->withStringBody(json_encode([
                'error' => 'Failed to fetch scheduling logs: ' . $e->getMessage()
            ]));
            return $this->response->withStatus(500);
        }
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