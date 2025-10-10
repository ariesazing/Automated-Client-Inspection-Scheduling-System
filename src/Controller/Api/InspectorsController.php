<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;

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
        $inspectors = $this->Inspectors->find();
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($inspectors));
    }

    public function getInspectors()
    {
        $inspectors = $this->Inspectors->find()->contain(['Users']);
        return $this->response->withType('application/json')
            ->withStringBody(json_encode(['data' => $inspectors]));
    }

    public function view($id = null)
    {
        $inspector = $this->Inspectors->get($id, [
            'contain' => ['Inspectors'],
        ]);

        $this->set(compact('inspector'));
    }
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $inspector = $this->Inspectors->newEmptyEntity();
        if ($this->request->is('post')) {
            $inspector = $this->Inspectors->patchEntity($inspector, $this->request->getData());
            if ($this->Inspectors->save($inspector)) {
                $result = ['status' => 'success', 'message' => 'The Inspector has been saved.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The Inspector could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
    }

    /**
     * Edit method
     *
     * @param string|null $id Inspector id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $inspector = $this->Inspectors->get($id, [
            'contain' => [],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $inspector = $this->Inspectors->patchEntity($inspector, $this->request->getData());
            if ($this->Inspectors->save($inspector)) {
                $result = ['status' => 'success', 'message' => 'The Inspector has been saved.'];
            } else {
                $result = ['status' => 'error', 'message' => 'The Inspector could not be saved. Please, try again.'];
            }
            return $this->response->withType('application/json')
                ->withStringBody(json_encode($result));
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($inspector));
    }

    /**
     * Delete method
     *
     * @param string|null $id Inspector id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $inspector = $this->Inspectors->get($id);
        if ($this->Inspectors->delete($inspector)) {
            $result = ['status' => 'success', 'message' => 'The inspector has been deleted.'];
        } else {
            $result = ['status' => 'error', 'message' => 'The inspector could not be deleted. Please, try again.'];
        }
        return $this->response->withType('application/json')
            ->withStringBody(json_encode($result));
    }
}
