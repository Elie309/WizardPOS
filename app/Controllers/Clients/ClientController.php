<?php

namespace App\Controllers\Clients;

use App\Controllers\BaseController;
use App\Entities\Clients\ClientEntity;
use App\Models\Clients\ClientModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function index()
    {
        $perPage = esc($this->request->getVar('perPage')) ?? 10;
        $perPage = $perPage ? intval($perPage) : 10;

        $page = esc($this->request->getVar('page')) ?? 1;
        $page = $page ? intval($page) : 1;

        $clientModel = new ClientModel();

        $productsQuery = $clientModel->select("clients.*");

        $search = esc($this->request->getVar('search'));

        if ($search) {
            $productsQuery->groupStart()
                ->like('clients.client_first_name', $search)
                ->orLike('clients.client_last_name', $search)
                ->orLike('clients.client_email', $search)
                ->orLike('clients.client_phone_number', $search)
                ->orLike('CONCAT(clients.client_first_name, " ", clients.client_last_name)', $search)
                ->groupEnd();
        }




        $clients = $productsQuery->paginate($perPage, 'default', $page);

        return $this->response->setJSON([
            'perPage' => $perPage,
            'page' => $page,
            'currentPage' => $clientModel->pager->getCurrentPage(),
            'pageCount' => $clientModel->pager->getPageCount(),
            'total' => $clientModel->pager->getTotal(),
            'clients' => $clients,

        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }


    public function show($id)
    {

        $ClientModel = new ClientModel();

        $client = $ClientModel->find($id);

        if (!$client) {
            return $this->response->setJSON([
                'message' => 'Client not found',
                'errors' => $ClientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON([
            'data' => $client,
            'message' => 'Client found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function create()
    {

        $clientModel = new ClientModel();
        $clientEntity = new ClientEntity();

        $clientEntity->fill($this->request->getPost());

        if (!$clientModel->save($clientEntity)) {
            return $this->response->setJSON([
                'message' => 'Client not created',
                'errors' => $clientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => $clientEntity,
            'message' => 'Client created',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function update($id)
    {

        $clientModel = new ClientModel();
        $clientEntity = new ClientEntity();

        $clientEntity->fill($this->request->getPost());

        $oldClient = $clientModel->find($id);
        if(!$oldClient){
            return $this->response->setJSON([
                'message' => 'Client not found',
                'errors' => $clientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if($oldClient->client_phone_number === $clientEntity->client_phone_number){
            //Delete phone number from the entity
            unset($clientEntity->client_phone_number);
        }

        if (!$clientModel->update($id, $clientEntity)) {
            return $this->response->setJSON([
                'message' => 'Client not updated',
                'errors' => $clientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => $clientEntity,
            'message' => 'Client updated',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function delete($id)
    {

        $clientModel = new ClientModel();

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to delete client',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        if(!$clientModel->find($id)){
            return $this->response->setJSON([
                'message' => 'Client not found',
                'errors' => $clientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$clientModel->delete($id)) {
            return $this->response->setJSON([
                'message' => 'Client not deleted',
                'errors' => $clientModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => null,
            'message' => 'Client deleted',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
