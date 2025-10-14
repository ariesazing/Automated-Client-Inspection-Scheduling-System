<?php

declare(strict_types=1);

namespace App\Controller;

/**
 * Availabilities Controller
 *
 * @property \App\Model\Table\AvailabilitiesTable $Availabilities
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AvailabilitiesController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['Inspectors'],
        ];
        $availabilities = $this->paginate($this->Availabilities);
        $availability = $this->Availabilities->newEmptyEntity();

        $this->set(compact('availabilities', 'availability'));
    }
}
