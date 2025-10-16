<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Inspectors Controller
 *
 * @property \App\Model\Table\InspectorsTable $Inspectors
 * @method \App\Model\Entity\Inspector[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class InspectorsController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $inspector = $this->Inspectors->newEmptyEntity();

        $this->set(compact('inspector'));
    }
}
