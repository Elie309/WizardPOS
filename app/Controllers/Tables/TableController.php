<?php

namespace App\Controllers\Tables;

use App\Controllers\BaseController;
use App\Entities\Tables\TableEntity;
use App\Models\Tables\TableModel;
use CodeIgniter\HTTP\ResponseInterface;

class TableController extends BaseController
{
    public function index()
    {
        $tableModel = new TableModel();
        $tables = $tableModel->findAll();

        return $this->response->setJSON($tables)->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function show($id)
    {
        $tableModel = new TableModel();
        $table = $tableModel->find($id);

        if (!$table) {
            return $this->response->setJSON([
                'message' => 'Table not found',
                'errors' => $tableModel->errors(),
                'table' => null
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'message' => 'Table found',
            'errors' => null,
            'table' => $table
            ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function activeTables(){
        $tableModel = new TableModel();
        $tables = $tableModel->where('table_is_active', 1)->findAll();

        return $this->response->setJSON($tables)->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function create()
    {
        $tableModel = new TableModel();
        $tableEntity = new TableEntity();

        $role = $this->user->role;

        if ($role != 'admin' && $role != 'manager') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to create table',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $tableEntity->fill($this->request->getPost());

        if (!$tableModel->save($tableEntity)) {
            return $this->response->setJSON([
                'message' => 'Table not created',
                'errors' => $tableModel->errors(),
                'data' => null
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'message' => 'Table created',
            'errors' => null,
            'data' => $tableEntity
            ])->setStatusCode(ResponseInterface::HTTP_CREATED);

    }

    public function update($id)
    {
        $tableModel = new TableModel();
        $tableEntity = new TableEntity();

        $role = $this->user->role;

        if ($role != 'admin' && $role != 'manager') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to create table',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $table = $tableModel->find($id);


        if (!$table) {
            return $this->response->setJSON([
                'message' => 'Table not found',
                'errors' => $tableModel->errors(),
                'data' => null
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $tableEntity->fill($this->request->getPost());

        if (!$tableModel->update($id, $tableEntity)) {
            return $this->response->setJSON([
                'message' => 'Table not updated',
                'errors' => $tableModel->errors(),
                'data' => null
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'message' => 'Table updated',
            'errors' => null,
            'data' => $tableEntity
            ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function delete($id)
    {
        
        $tableModel = new TableModel();

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to delete table',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $table = $tableModel->find($id);

        if (!$table) {
            return $this->response->setJSON([
                'message' => 'Table not found',
                'errors' => $tableModel->errors(),
                'data' => null
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$tableModel->delete($id)) {
            return $this->response->setJSON([
                'message' => 'Table not deleted',
                'errors' => $tableModel->errors(),
                'data' => null
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'message' => 'Table deleted',
            'errors' => null,
            'data' => $table
            ])->setStatusCode(ResponseInterface::HTTP_OK);
            


    }

}
