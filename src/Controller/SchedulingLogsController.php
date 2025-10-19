<?php
declare(strict_types=1);

namespace App\Controller;

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
        $schedulingLogs = $this->SchedulingLogs->newEmptyEntity();

        $this->set(compact('schedulingLogs'));
    }
}
