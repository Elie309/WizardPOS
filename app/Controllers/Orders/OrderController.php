<?php

namespace App\Controllers\Orders;

use App\Controllers\BaseController;
use App\Entities\Orders\OrderEntity;
use App\Models\Orders\OrderItemModel;
use App\Models\Orders\OrderModel;
use App\Models\Settings\EmployeeModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrderController extends BaseController
{

    private $statuses = [
        "on-going",
        "completed",
        "cancelled"
    ];

    private $types = [
        "take-away",
        "dine-in",
        "delivery"
    ];

    public function index()
    {
        $orderModel = new OrderModel();

        //Get date
        $date = esc($this->request->getGet('date'));
        $orders = null;

        //Check if date is valid
        if (!$date) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Date invalid',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $orders = $orderModel->select('orders.order_id,
        orders.order_client_id,
        orders.order_employee_id,
        orders.order_type,
        orders.order_reference,
        orders.order_date,
        orders.order_time,
        orders.order_note,
        orders.order_subtotal,
        orders.order_discount,
        orders.order_tax,
        orders.order_total,
        orders.order_status,
        orders.order_created_at,
        orders.order_updated_at,
        orders.order_deleted_at,
        CONCAT(clients.client_first_name, " ", clients.client_last_name) as client_name,
        clients.client_phone_number,
        CONCAT(employees.employee_first_name, " ", employees.employee_last_name) as employee_name,
        order_items.order_item_id,
        order_items.order_item_product_id,
        order_items.order_item_quantity,
        order_items.order_item_total,
        products.product_name,
        products.product_price')
            ->join('clients', 'clients.client_id = orders.order_client_id')
            ->join('employees', 'employees.employee_id = orders.order_employee_id')
            ->join('order_items', 'order_items.order_id = orders.order_id', 'left')
            ->join('products', 'products.product_id = order_items.order_item_product_id', 'left')
            ->where('order_date', $date)
            ->findAll();

        $groupedOrders = [];

        log_message('info', 'Orders: ' . json_encode($orders));

        foreach ($orders as $order) {
            $orderId = $order->order_id;

            if (!isset($groupedOrders[$orderId])) {
                $groupedOrders[$orderId] = [
                    'order_id' => $order->order_id,
                    'order_client_id' => $order->order_client_id,
                    'order_employee_id' => $order->order_employee_id,
                    'order_type' => $order->order_type,
                    'order_reference' => $order->order_reference,
                    'order_date' => $order->order_date,
                    'order_time' => $order->order_time,
                    'order_note' => $order->order_note,
                    'order_subtotal' => $order->order_subtotal,
                    'order_discount' => $order->order_discount,
                    'order_tax' => $order->order_tax,
                    'order_total' => $order->order_total,
                    'order_status' => $order->order_status,
                    'order_created_at' => $order->order_created_at,
                    'order_updated_at' => $order->order_updated_at,
                    'order_deleted_at' => $order->order_deleted_at,


                    'client_name' => $order->client_name,
                    'client_phone_number' => $order->client_phone_number,
                    'employee_name' => $order->employee_name,
                    'order_items' => []
                ];
            }
            if ($order->order_item_id) {
                $groupedOrders[$orderId]['order_items'][] = [
                    'order_item_id' => $order->order_item_id,
                    'order_id' => $order->order_id,
                    'order_item_product_id' => $order->order_item_product_id,
                    'order_item_quantity' => $order->order_item_quantity,
                    'order_item_total' => $order->order_item_total,
                    'product_name' => $order->product_name,
                    'product_price' => $order->product_price
                ];
            }
        }

        $orders = array_values($groupedOrders);

        return $this->response->setJSON([
            'data' => $orders,
            'message' => 'Orders found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function create()
    {
        $orderModel = new OrderModel();
        $orderEntity = new OrderEntity();
        $orderEntity->fill($this->request->getPost());

        $employee = $this->getEmployeeByEmail($this->user->email);
        if (!$employee) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Employee not found',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $orderEntity->order_employee_id = $employee->employee_id;
        unset($orderEntity->order_created_at, $orderEntity->order_updated_at, $orderEntity->order_deleted_at, $orderEntity->order_reference);
        $orderEntity->order_reference = strtoupper(substr(uniqid(sha1(time())), 0, 6));


        if (!$orderModel->save($orderEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to create order',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $orderEntity->order_id = $orderModel->getInsertID();

        $newReference = $orderModel->generateReference($orderEntity->order_id);

        $orderModel->update($orderEntity->order_id, ['order_reference' => $newReference]);

        $order = $this->getOrderWithDetails($orderEntity->order_id);

        return $this->response->setJSON([
            'data' => $order,
            'message' => 'Order created',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    private function getEmployeeByEmail($email)
    {
        $employeeModel = new EmployeeModel();
        return $employeeModel->select('employee_id, employee_email')->where('employee_email', $email)->first();
    }




    public function show($id)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);

        if (!$order) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order not found',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $order = $this->getOrderWithDetails($id);

        $orderItemModel = new OrderItemModel();
        $orderItems = $orderItemModel
            ->select("order_items.*, products.product_sku, products.product_name, products.product_price")
            ->join('products', 'products.product_id = order_items.order_item_product_id')
            ->where('order_id', $id)->findAll();

        $order->order_items = $orderItems;

        return $this->response->setJSON([
            'data' => $order,
            'message' => 'Order found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function update($id)
    {
        $orderModel = new OrderModel();
        $orderEntity = new OrderEntity();
        $oldOrder = $orderModel->find($id);

        if (!$oldOrder) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order not found',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $orderEntity->fill($this->request->getPost());
        unset($orderEntity->order_created_at, $orderEntity->order_updated_at, $orderEntity->order_deleted_at, $orderEntity->order_reference);

        //unset employee_id
        unset($orderEntity->order_employee_id);

        // Check the order reference if it does not start with ORD-
        if (strpos($oldOrder->order_reference, 'ORD-') === false) {
            $orderEntity->order_reference = $orderModel->generateReference($id);
        }


        if (!$orderModel->update($id, $orderEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to update order',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $order = $this->getOrderWithDetails($id);

        return $this->response->setJSON([
            'data' => $order,
            'message' => 'Order updated',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function delete($id)
    {
        $orderModel = new OrderModel();

        if ($this->user->role !== 'admin') {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Unauthorized',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $order = $orderModel->find($id);

        if (!$order) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order not found',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$orderModel->delete($id)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to delete order',
                'errors' => $orderModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => null,
            'message' => 'Order deleted',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }


    public function statuses()
    {
        return $this->response->setJSON([
            'data' => $this->statuses,
            'message' => 'Statuses found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function types()
    {
        return $this->response->setJSON([
            'data' => $this->types,
            'message' => 'Types found',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }


    //METHODS

    private function getOrderWithDetails($orderId)
    {
        $orderModel = new OrderModel();
        return $orderModel->select('orders.*,
                CONCAT(clients.client_first_name, " ", clients.client_last_name) as client_name,
                clients.client_phone_number,
                CONCAT(employees.employee_first_name, " ", employees.employee_last_name) as employee_name')
            ->join('clients', 'clients.client_id = orders.order_client_id')
            ->join('employees', 'employees.employee_id = orders.order_employee_id')
            ->where('order_id', $orderId)
            ->first();
    }
}
