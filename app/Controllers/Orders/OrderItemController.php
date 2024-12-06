<?php

namespace App\Controllers\Orders;

use App\Controllers\BaseController;
use App\Entities\Orders\OrderItemEntity;
use App\Models\Orders\OrderItemModel;
use CodeIgniter\HTTP\ResponseInterface;

class OrderItemController extends BaseController
{
    public function list($orderId)
    {
        $orderItemModel = new OrderItemModel();
        $orderItems = $orderItemModel
            ->select("order_items.*, products.product_sku, products.product_name, products.product_price")
            ->join('products', 'products.product_id = order_items.order_item_product_id')
            ->where('order_id', $orderId)->findAll();

        return $this->response->setJSON([
            'data' => $orderItems,
            'message' => 'Order items retrieved',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function add()
    {
        $orderItemModel = new OrderItemModel();
        $orderItemEntity = new OrderItemEntity();
        $orderItemEntity->fill($this->request->getPost());

        unset($orderItemEntity->order_item_id);

        if (!$orderItemModel->save($orderItemEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to create order item',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => $orderItemEntity,
            'message' => 'Order item created',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }


    public function delete($id)
    {
        $orderItemModel = new OrderItemModel();
        $orderItem = $orderItemModel->find($id);

        if (!$orderItem) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order item not found',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$orderItemModel->delete($id)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to delete order item',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => null,
            'message' => 'Order item deleted',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }

    //Bulk add
    public function bulkAdd($orderId)
    {

        try {

        $orderItemModel = new OrderItemModel();
        $orderItemEntities = [];
        $orderItemData = $this->request->getJSON();


            foreach ($orderItemData as $orderItem) {
                $orderItemEntity = new OrderItemEntity();
                $orderItemEntity->fill((array) $orderItem);
                unset($orderItemEntity->order_item_id);
                $orderItemEntity->order_id = $orderId;
                $orderItemEntities[] = $orderItemEntity;
            }

            if (!$orderItemModel->insertBatch($orderItemEntities)) {
                return $this->response->setJSON([
                    'data' => null,
                    'message' => 'Failed to create order items',
                    'errors' => $orderItemModel->errors()
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->response->setJSON([
                'data' => $orderItemEntities,
                'message' => 'Order items created',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to create order items',
                'errors' => $e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    //Bulk delete
    public function bulkDelete()
    {
        $orderItemModel = new OrderItemModel();
        $orderItemIds = $this->request->getJSON();

        if (!$orderItemModel->delete($orderItemIds)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to delete order items',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response->setJSON([
            'data' => null,
            'message' => 'Order items deleted',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }
}
