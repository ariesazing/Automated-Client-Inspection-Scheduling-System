<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\ORM\TableRegistry;

/**
 * Dashboard Controller
 */
class DashboardController extends AppController
{
    public function initialize(): void
    {
        parent::initialize();
        $this->Inspections = TableRegistry::getTableLocator()->get('Inspections');
        $this->Clients = TableRegistry::getTableLocator()->get('Clients'); // Add this line
        $this->Inspectors = TableRegistry::getTableLocator()->get('Inspectors'); // Add this line
        $this->Users = TableRegistry::getTableLocator()->get('Users'); // Add this line
    }

    public function index()
    {
        // Remove these lines as they're not needed
        // $this->fetchTable('Inspections');
        // $this->fetchTable('Clients');
        // $this->fetchTable('Inspectors');
        // $this->fetchTable('Users');

        // Get counts for dashboard cards
        $totalInspections = $this->Inspections->find()->count();
        $completedInspections = $this->Inspections->find()
            ->where(['status' => 'completed'])
            ->count();
        $scheduledInspections = $this->Inspections->find()
            ->where(['status' => 'scheduled'])
            ->count();
        $totalClients = $this->Clients->find()->count();
        $activeInspectors = $this->Inspectors->find()
            ->where(['status' => 'available'])
            ->count();

        // Calculate completion rate
        $completionRate = $totalInspections > 0 ?
            round(($completedInspections / $totalInspections) * 100, 1) : 0;

        $scheduledRate = $totalInspections > 0 ?
            round(($scheduledInspections / $totalInspections) * 100, 1) : 0;

        // Get recent inspections
        $recentInspections = $this->Inspections->find()
            ->contain(['Clients', 'Inspectors'])
            ->order(['Inspections.created_at' => 'DESC'])
            ->limit(5)
            ->toArray();

        // Get inspections by status for chart
        $inspectionsByStatus = $this->Inspections->find()
            ->select([
                'status',
                'count' => $this->Inspections->find()->func()->count('*')
            ])
            ->group('status')
            ->toArray();

        // Get clients by type
        $clientsByType = $this->Clients->find()
            ->select([
                'type',
                'count' => $this->Clients->find()->func()->count('*')
            ])
            ->group('type')
            ->toArray();

        // Get risk level distribution
        $riskLevels = $this->Clients->find()
            ->select([
                'risk_level',
                'count' => $this->Clients->find()->func()->count('*')
            ])
            ->group('risk_level')
            ->toArray();

        $this->set(compact(
            'totalInspections',
            'completedInspections',
            'scheduledInspections',
            'totalClients',
            'activeInspectors',
            'recentInspections',
            'inspectionsByStatus',
            'clientsByType',
            'riskLevels',
            'completionRate',
            'scheduledRate'
        ));
    }
}