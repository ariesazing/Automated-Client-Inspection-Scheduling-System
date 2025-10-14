<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * Availabilities Controller
 *
 * @property \App\Model\Table\AvailabilitiesTable $Availabilities
 * @method \App\Model\Entity\Availability[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AvailabilitiesController extends AppController
{
    /**
     *
     * Returns all inspector availabilities formatted for FullCalendar
     */
    public function index($inspector_id = null)
    {
        $this->request->allowMethod(['get']);

        $availabilitiesTable = $this->fetchTable('Availabilities');

        $query = $availabilitiesTable->find()
            ->contain(['Inspectors'])
            ->select([
                'Availabilities.id',
                'Availabilities.inspector_id',
                'Availabilities.available_date',
                'Availabilities.is_available',
                'Availabilities.reason',
                'Inspectors.name'

            ])
            ->order(['Availabilities.available_date' => 'ASC']);

        if ($inspector_id !== null) {
            $query->where(['Availabilities.inspector_id' => $inspector_id]);
        }
        $availabilities = $query->toList();

        $events = [];
        foreach ($availabilities as $a) {
            //\Cake\Log\Log::debug('Inspector ID: ' . $a->inspector_id);
            //var_dump(pr($availabilities));die;
            $events[] = [
                'id' => $a->id,
                'title' => $a->inspector->name .
                    ($a->is_available ? ' (Available)' : ' (Unavailable)') .
                    (!empty($a->reason) ? ' - ' . $a->reason : ''),
                'start' => $a->available_date,
                'color' => $a->is_available ? '#28a745' : '#dc3545',
                'extendedProps' => [
                    'inspector_id' => $a->inspector_id,
                    'reason' => $a->reason,
                    'is_available' => $a->is_available
                ]

            ];
        }
        $this->set('availability', $this->Availabilities->newEmptyEntity());
        return $this->response
            ->withType('application/json')
            ->withStringBody(json_encode(['data' => $events]));
    }

    public function getInspectorAvailabilities($inspector_id = null)
    {
        $availabilities = $this->Availabilities->find()
            ->contain(['Inspectors'])
            ->where(['inspector_id' => $inspector_id])
            ->order(['available_date' => 'ASC'])
            ->toList();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $availabilities]));
    }


    public function getAvailabilities()
    {
        $availabilities = $this->Availabilities->find()
            ->contain(['Inspectors'])
            ->order(['Availabilities.available_date' => 'ASC'])
            ->toList();

        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $availabilities]));
    }



    public function editAvailabilities($id = null)
    {
        $availability = $this->Availabilities->get($id, [
            'contain' => ['Inspectors'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $availability = $this->Availabilities->patchEntity($availability, $this->request->getData());
            if ($this->Availabilities->save($availability)) {
                $result = ['status' => 'success', 'message' => 'The Inspector has been saved.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The Inspector could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($availability));
    }
}
