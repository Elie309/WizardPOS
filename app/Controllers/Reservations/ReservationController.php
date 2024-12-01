<?php

namespace App\Controllers\Reservations;

use App\Controllers\BaseController;
use App\Entities\Reservations\ReservationEntity;
use App\Models\Reservations\ReservationModel;
use App\Models\Settings\EmployeeModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReservationController extends BaseController
{

    private $statuses = [
        'pending',
        'confirmed',
        'cancelled',
        'completed'
    ];

    public function index()
    {

        $reservationModel = new ReservationModel();

        $date = esc($this->request->getGet('date'));

        $date = date('Y-m-d', strtotime($date));


        $reservations = $reservationModel->select('reservations.*, 
                CONCAT(clients.client_first_name, " ",clients.client_last_name ) as client_name,
                clients.client_phone_number,
                CONCAT(employees.employee_first_name, " ", employees.employee_last_name) as employee_name,
                restaurant_tables.table_is_active
                ')
            ->join('clients', 'clients.client_id = reservations.reservation_client_id')
            ->join('restaurant_tables', 'restaurant_tables.table_id = reservations.reservation_table_id')
            ->join('employees', 'employees.employee_id = reservations.reservation_employee_id')
            ->where('restaurant_tables.table_is_active', 1)
            ->where('reservations.reservation_date', $date)
            ->findAll();



        return $this->response->setJSON([
            'data' => $reservations,
            'message' => 'Reservations found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function create()
    {
        $reservationModel = new ReservationModel();

        $reservationEntity = new ReservationEntity();

        $reservationEntity->fill($this->request->getPost());


        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->select('employee_id, employee_email')->where('employee_email', $this->user->email)->first();


        if (!$employee) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Employee not found',
                'errors' => $employeeModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $reservationEntity->reservation_employee_id = $employee->employee_id;

        if (!$reservationModel->save($reservationEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to create reservation',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => $reservationEntity,
            'message' => 'Reservation created',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }


    public function show($id)
    {
        $reservationModel = new ReservationModel();



        $reservation = $reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Reservation not found',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $reservation = $reservationModel->select('reservations.*, 
                CONCAT(clients.client_first_name, " ",clients.client_last_name ) as client_name,
                clients.client_phone_number,
                CONCAT(employees.employee_first_name, " ", employees.employee_last_name) as employee_name
                ')
            ->join('clients', 'clients.client_id = reservations.reservation_client_id')
            ->join('employees', 'employees.employee_id = reservations.reservation_employee_id')
            ->where('reservations.reservation_id', $id)
            ->first();

        return $this->response->setJSON([
            'data' => $reservation,
            'message' => 'Reservation found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function update($id)
    {
        $reservationModel = new ReservationModel();

        $reservationEntity = new ReservationEntity();


        $reservation = $reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Reservation not found',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }


        $reservationEntity->fill($this->request->getPost());

        unset($reservationEntity->reservation_created_at);
        unset($reservationEntity->reservation_updated_at);
        unset($reservationEntity->reservation_employee_id);


        if (!$reservationModel->update($id, $reservationEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to update reservation',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }


        return $this->response->setJSON([
            'data' => $reservationEntity,
            'message' => 'Reservation updated',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function delete($id)
    {

        $reservationModel = new ReservationModel();


        if ($this->user->role !== 'admin') {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Unauthorized',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $reservation = $reservationModel->find($id);

        if (!$reservation) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Reservation not found',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$reservationModel->delete($id)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to delete reservation',
                'errors' => $reservationModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => null,
            'message' => 'Reservation deleted',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }


    public function statuses()
    {

        return $this->response->setJSON([
            'data' => $this->statuses,
            'message' => 'Available statuses',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
