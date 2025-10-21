<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class InspectorSessionDataComponent extends Component
{
    protected $_inspectorSessionData = null;

    /**
     * Get inspector data for the currently logged-in user
     */
    public function getData($user = null)
    {
        if ($this->_inspectorSessionData === null) {
            $controller = $this->getController();
            $auth = $user ?? $controller->Auth->user();

            if ($auth && $auth['role'] === 'inspector') {
                $inspectorsTable = TableRegistry::getTableLocator()->get('Inspectors');
                $this->_inspectorSessionData = $inspectorsTable->find()
                    ->select(['id', 'name', 'specialization', 'status'])
                    ->where(['user_id' => $auth['id']])
                    ->first();
            }
        }

        return $this->_inspectorSessionData;
    }

    /**
     * Get just the inspector ID
     */
    public function getId()
    {
        $data = $this->getData();
        return $data ? $data->id : null;
    }
}
