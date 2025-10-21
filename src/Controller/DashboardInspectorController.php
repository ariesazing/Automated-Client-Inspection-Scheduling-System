<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Dashboard Controller
 */
class DashboardInspectorController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Inspections = TableRegistry::getTableLocator()->get('Inspections');
        $this->Clients = TableRegistry::getTableLocator()->get('Clients');
        $this->Inspectors = TableRegistry::getTableLocator()->get('Inspectors');
        $this->Users = TableRegistry::getTableLocator()->get('Users');
    }

    public function index()
    {
        $auth = $this->Auth->user();
        if (empty($auth['inspector_id'])) {
            $inspectorsTable = TableRegistry::getTableLocator()->get('Inspectors');
            $inspector = $inspectorsTable->find()
                ->select(['id'])
                ->where(['user_id' => $auth['id']])
                ->first();

            if ($inspector) {
                $auth['inspector_id'] = $inspector->id;
                $this->getRequest()->getSession()->write('Auth', $auth);
            } else {
                $this->Flash->error('Inspector profile not found.');
                return $this->redirect(['controller' => 'Users', 'action' => 'login']);
            }
        }

        $inspectorId = $auth['inspector_id'];
        // Get counts for dashboard cards - filtered by inspector
        $totalInspections = $this->Inspections->find()
            ->where(['inspector_id' => $inspectorId])
            ->count();

        $completedInspections = $this->Inspections->find()
            ->where(['inspector_id' => $inspectorId, 'status' => 'completed'])
            ->count();

        $scheduledInspections = $this->Inspections->find()
            ->where(['inspector_id' => $inspectorId, 'status' => 'scheduled'])
            ->count();

        // Get unique clients assigned to this inspector
        $totalClients = $this->Inspections->find()
            ->where(['inspector_id' => $inspectorId])
            ->select(['client_id'])
            ->distinct(['client_id'])
            ->count();

        // Calculate completion rate
        $completionRate = $totalInspections > 0 ?
            round(($completedInspections / $totalInspections) * 100, 1) : 0;

        $scheduledRate = $totalInspections > 0 ?
            round(($scheduledInspections / $totalInspections) * 100, 1) : 0;

        // Get recent inspections for this inspector
        $recentInspections = $this->Inspections->find()
            ->contain(['Clients', 'Inspectors'])
            ->where(['Inspections.inspector_id' => $inspectorId])
            ->order(['Inspections.created_at' => 'DESC'])
            ->limit(5)
            ->toArray();

        // Get inspections by status for chart - filtered by inspector
        $inspectionsByStatus = $this->Inspections->find()
            ->select([
                'status',
                'count' => $this->Inspections->find()->func()->count('*')
            ])
            ->where(['inspector_id' => $inspectorId])
            ->group('status')
            ->toArray();

        // Get clients by type for this inspector's inspections
        $clientsByType = $this->Inspections->find()
            ->contain(['Clients'])
            ->select([
                'Clients.type',
                'count' => $this->Inspections->find()->func()->count('*')
            ])
            ->where(['Inspections.inspector_id' => $inspectorId])
            ->group('Clients.type')
            ->toArray();

        // Get risk level distribution for this inspector's clients
        $riskLevels = $this->Inspections->find()
            ->contain(['Clients'])
            ->select([
                'Clients.risk_level',
                'count' => $this->Inspections->find()->func()->count('*')
            ])
            ->where(['Inspections.inspector_id' => $inspectorId])
            ->group('Clients.risk_level')
            ->toArray();

        $this->set(compact(
            'totalInspections',
            'completedInspections',
            'scheduledInspections',
            'totalClients',
            'recentInspections',
            'inspectionsByStatus',
            'clientsByType',
            'riskLevels',
            'completionRate',
            'scheduledRate',
            'inspectorId'
        ));
    }
}
