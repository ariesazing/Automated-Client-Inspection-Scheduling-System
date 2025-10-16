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
    public function index()
    {
        $inspections = $this->Inspections->find()
            ->contain(['Inspectors', 'Clients']);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($inspections));
    }

    public function view($id = null)
    {
        $inspection = $this->Inspections->get($id, [
            'contain' => ['Clients', 'Inspectors', 'InspectionResults', 'SchedulingLogs'],
        ]);

        $this->set(compact('inspection'));
    }

    public function getInspections()
    {
        $inspections = $this->Inspections->find()
            ->contain(['Clients', 'Inspectors', 'InspectionResults', 'SchedulingLogs']);

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $inspections]));
    }

    public function edit($id = null)
    {
        $inspection = $this->Inspections->get($id, [
            'contain' => ['Clients', 'Inspectors', 'SchedulingLogs']
        ]);

        $client_id = $inspection->client_id;
        $assignment = $this->Inspections->autoAssignAndSchedule($client_id);

        if ($assignment) {
            $inspection->inspector_id = $assignment['inspector_id'];
            $inspection->scheduled_date = $assignment['scheduled_date'];
        }

        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspection = $this->Inspections->patchEntity($inspection, $this->request->getData());
            $userId = $this->Auth->user('id');

            \Cake\Log\Log::debug('Incoming data: ' . json_encode($this->request->getData()));

            $success = $this->Inspections->save($inspection, ['userId' => $userId]);

            if (!$success) {
                \Cake\Log\Log::debug('Save failed: ' . json_encode($inspection->getErrors()));
            }

            $result = $success
                ? ['status' => 'success', 'message' => 'The inspection has been updated.']
                : ['status' => 'error', 'message' => 'The inspection could not be updated. Please try again.'];

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

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspection = $this->Inspections->get($id);

        $success = $this->Inspections->delete($inspection);

        $result = $success
            ? ['status' => 'success', 'message' => 'The Inspection has been deleted.']
            : ['status' => 'error', 'message' => 'The inspection could not be deleted. Please, try again.'];

        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}