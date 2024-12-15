<?php

namespace App\Controllers\Orders;

use App\Controllers\BaseController;
use App\Models\Orders\OrderModel;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
{

    //By Date
    public function byDate()
    {
        $orderModel = new OrderModel();

        $startDate = esc($this->request->getGet('start_date'));
        $endDate = esc($this->request->getGet('end_date'));

        if (empty($startDate)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Start date is required',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $orders = $orderModel
            ->select("orders.*, CONCAT(clients.client_first_name, ' ', clients.client_last_name) as client_name, 
            CONCAT(employees.employee_first_name, ' ',employees.employee_last_name) as employee_name")
            ->join('clients', 'clients.client_id = orders.order_client_id')
            ->join('employees', 'employees.employee_id = orders.order_employee_id');
            

        if (empty($endDate)) {
            $orders->where('order_date', $startDate);
        } else {
            $orders->where('order_date >=',  $startDate);
            $orders->where('order_date <=',  $endDate);
        }

        $orders->groupBy('orders.order_id');
        $result = $orders->findAll();

        $totalsQuery = $orderModel
            ->select("SUM(order_total) as total_sales, COUNT(order_id) as total_orders");
        if (empty($endDate)) {
            $totalsQuery->where('order_date', $startDate);
        } else {
            $totalsQuery->where('order_date >=',  $startDate);
            $totalsQuery->where('order_date <=',  $endDate);
        }

        $totals = $totalsQuery->get()->getRow();

        $data = [
            'orders' => $result,
            'totals' => $totals
        ];

        return $this->response->setJSON([
            'data' => $data,
            'message' => 'Orders retrieved',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
