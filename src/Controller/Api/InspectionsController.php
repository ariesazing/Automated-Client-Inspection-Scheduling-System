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

        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspection = $this->Inspections->patchEntity($inspection, $this->request->getData());
            $userId = $this->Auth->user('id');

            // Only auto-assign if inspector_id is empty/being cleared AND client exists
            $data = $this->request->getData();
            if (empty($data['inspector_id']) && !empty($inspection->client_id)) {
                // Use the ClientsTable method instead - THE WORKING ONE
                $clientsTable = $this->getTableLocator()->get('Clients');
                $success = $clientsTable->fullAutoCreateForClient($this->Inspections, $inspection->client_id);
                
                if ($success) {
                    $result = ['status' => 'success', 'message' => 'The inspection has been updated with auto-assignment.'];
                } else {
                    $result = ['status' => 'error', 'message' => 'The inspection could not be auto-assigned. Please try again.'];
                }
                
                return $this->response->withType('application/json')
                    ->withStringBody(json_encode($result));
            }

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

        // Handle GET request - return data for form
        $clients = $this->Inspections->Clients->find('list', ['limit' => 200])->all();
        $inspectors = $this->Inspections->Inspectors->find('list', ['limit' => 200])->all();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'inspection' => $inspection,
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

    public function autoCreate($clientId = null)
    {
        if ($clientId) {
            // Create for specific client using the WORKING method
            $clientsTable = $this->getTableLocator()->get('Clients');
            $success = $clientsTable->fullAutoCreateForClient($this->Inspections, $clientId);
            $message = $success
                ? 'Inspection auto-created for client'
                : 'Failed to auto-create inspection';
                
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'status' => $success ? 'success' : 'error',
                    'message' => $message
                ]));
        }
        
        // If you need bulk creation, you'll need to implement it using the working method
        $message = "Bulk auto-creation not implemented. Use individual client creation.";
        
        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => 'error',
                'message' => $message
            ]));
    }
}