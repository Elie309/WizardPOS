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

    public function add($orderId)
    {
        $orderItemModel = new OrderItemModel();
        $orderItemEntity = new OrderItemEntity();
        $orderItemEntity->fill($this->request->getPost());

        unset($orderItemEntity->order_item_id);
        $orderItemEntity->order_id = $orderId;

        if (!$orderItemModel->save($orderItemEntity)) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to create order item',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
        $orderItemEntity->order_item_id = $orderItemModel->getInsertID();

        return $this->response->setJSON([
            'data' => $orderItemEntity,
            'message' => 'Order item created',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);
    }


    public function delete($orderId, $itemId)
    {
        $orderItemModel = new OrderItemModel();
        $orderItem = $orderItemModel->find($itemId);

        if (!$orderItem) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order item not found',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if($orderItem->order_id != $orderId){
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order item not found',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }
        

        if (!$orderItemModel->delete($itemId)) {
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
            $orderItemData = $this->request->getJSON();

            $newItems = [];
            $existingItems = [];

            foreach ($orderItemData as $orderItem) {
                $orderItemEntity = new OrderItemEntity();
                $orderItemEntity->fill((array) $orderItem);
                $orderItemEntity->order_id = $orderId;

                if (isset($orderItemEntity->order_item_id)) {
                    $existingItems[] = $orderItemEntity;
                } else {
                    $newItems[] = $orderItemEntity;
                }
            }

            if (!empty($newItems) && !$orderItemModel->insertBatch($newItems)) {
                return $this->response->setJSON([
                    'data' => null,
                    'message' => 'Failed to create order items',
                    'errors' => $orderItemModel->errors()
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            if (!empty($existingItems) && !$orderItemModel->updateBatch($existingItems, 'order_item_id')) {
                return $this->response->setJSON([
                    'data' => null,
                    'message' => 'Failed to update order items',
                    'errors' => $orderItemModel->errors()
                ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->response->setJSON([
                'data' => array_merge($newItems, $existingItems),
                'message' => 'Order items processed',
                'errors' => null
            ])->setStatusCode(ResponseInterface::HTTP_CREATED);

            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Failed to process order items',
                'errors' => $e->getMessage()
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }
    }

    //Bulk delete
    public function bulkDelete($orderId)
    {
        $orderItemModel = new OrderItemModel();
        $orderItemIds = $this->request->getJSON();

        $orderItems = $orderItemModel->find($orderItemIds);

        if (!$orderItems) {
            return $this->response->setJSON([
                'data' => null,
                'message' => 'Order items not found',
                'errors' => $orderItemModel->errors()
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        foreach ($orderItems as $orderItem) {
            if($orderItem->order_id != $orderId){
                return $this->response->setJSON([
                    'data' => null,
                    'message' => 'Order items invalid',
                    'errors' => $orderItemModel->errors()
                ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }
        }

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
