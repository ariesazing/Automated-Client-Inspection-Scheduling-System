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
                $assignment = $this->Inspections->autoAssignAndSchedule($inspection->client_id);
                if ($assignment) {
                    $inspection->inspector_id = $assignment['inspector_id'];
                    $inspection->scheduled_date = $assignment['scheduled_date'];
                }
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

    /*public function forceCreate($clientId = null)
    {
        \Cake\Log\Log::info("=== FORCE CREATING INSPECTION FOR CLIENT #{$clientId} ===");

        $inspectionsTable = $this->getTableLocator()->get('Inspections');

        // Create WITHOUT inspector_id to avoid foreign key constraint
        $inspection = $inspectionsTable->newEntity([
            'client_id' => $clientId,
            'status' => 'pending',
            'created' => new \Cake\I18n\FrozenTime(),
            'modified' => new \Cake\I18n\FrozenTime()
            // NO inspector_id field!
        ]);

        $result = $inspectionsTable->save($inspection);

        if ($result) {
            \Cake\Log\Log::info("🎉 FORCE CREATE SUCCESS! ID: #{$inspection->id}");
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'message' => 'Inspection created with ID: ' . $inspection->id,
                    'inspection_id' => $inspection->id
                ]));
        } else {
            \Cake\Log\Log::error("💥 FORCE CREATE FAILED: " . json_encode($inspection->getErrors()));
            return $this->response->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => 'Failed to create inspection',
                    'errors' => $inspection->getErrors()
                ]));
        }
    }
        */

    public function autoCreate($clientId = null)
    {
        if ($clientId) {
            // Create for specific client
            $success = $this->Inspections->autoCreateForClient($clientId);
            $message = $success
                ? 'Inspection auto-created for client'
                : 'Failed to auto-create inspection';
        } else {
            // Create for all clients missing inspections
            $results = $this->Inspections->autoCreateForAllClients();
            $message = sprintf(
                'Created: %d, Failed: %d, Existing: %d',
                $results['created'],
                $results['failed'],
                $results['existing']
            );
        }

        return $this->response->withType('application/json')
            ->withStringBody(json_encode([
                'status' => $success ? 'success' : 'error',
                'message' => $message
            ]));
    }
}
