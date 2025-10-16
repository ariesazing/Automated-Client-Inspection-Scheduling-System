<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Inspections Controller
 *
 * @property \App\Model\Table\InspectionsTable $Inspections
 * @method \App\Model\Entity\Inspection[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectionsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $inspections = $this->Inspections->find()
            ->contain(['Inspectors', 'Clients']);
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($inspections));
    }

    /**
     * View method
     *
     * @param string|null $id Inspection id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $inspection = $this->Inspections->get($id, [
            'contain' => ['Clients', 'Inspectors', 'InspectionResults', 'SchedulingLogs'],
        ]);

        $this->set(compact('inspection'));
    }

    public function getInspections()
    {
        $inspections = $this->Inspections->find()->contain(['Clients', 'Inspectors', 'InspectionResults', 'SchedulingLogs']);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $inspections]));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */

    /**
     * Edit method
     *
     * @param string|null $id Inspection id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspection = $this->Inspections->get($id, [
            'contain' => ['Clients', 'Inspectors', 'SchedulingLogs']
        ]);

        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspection = $this->Inspections->patchEntity($inspection, $this->request->getData());
            $userId = $this->Auth->user('id');
            \Cake\Log\Log::debug('Incoming data: ' . json_encode($this->request->getData()));
            if (!$this->Inspections->save($inspection, ['userId' => $userId])) {
                \Cake\Log\Log::debug('Save failed: ' . json_encode($inspection->getErrors()));
            }
            if ($this->Inspections->save($inspection, ['userId' => $userId])) {
                $result = ['status' => 'success', 'message' => 'The inspection has been updated.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The inspection could not be updated. Please try again.'];
            }

            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }

        $clients = $this->Inspections->Clients->find('list', ['limit' => 200])->all();
        $inspectors = $this->Inspections->Inspectors->find('list', ['limit' => 200])->all();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'inspection' => $inspection->toArray(),
                'clients' => $clients,
                'inspectors' => $inspectors
            ]));
    }


    /**
     * Delete method
     *
     * @param string|null $id Inspection id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspection = $this->Inspections->get($id);
        if ($this->Inspections->delete($inspection)) {
            $result = ['status' => 'success', 'message' => 'The Inspection has been deleted.'];
        } else {
            $result = ['status' => 'error', 'message' => 'The inspection could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
