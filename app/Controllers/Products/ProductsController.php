<?php

namespace App\Controllers\Products;

use App\Controllers\BaseController;
use App\Entities\Products\ProductEntity;
use App\Models\Products\ProductModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

class ProductsController extends BaseController
{

    public function index()
    {
        $productModel = new ProductModel();

        // Set limit
        $perPage = esc($this->request->getVar('perPage')) ?? 10;
        $perPage = $perPage ? intval($perPage) : 10;

        $page = esc($this->request->getVar('page')) ?? 1;
        $page = $page ? intval($page) : 1;

        $productsQuery = $productModel->select('products.*, categories.category_name')
            ->join('categories', 'categories.category_id = products.product_category_id')
            ->where('product_is_active', 1);

        $search = esc($this->request->getGet('search'));

        if ($search) {
            $productsQuery
                ->like('product_name', $search)
                ->orLike('category_name', $search)
                ->orLike('product_sku', $search);
        }

        $products = $productsQuery->paginate($perPage, 'default', $page);

        if (empty($products)) {
            return $this->response
                ->setJSON([
                    'message' => 'No products found',
                ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response
            ->setJSON([
                'perPage' => $perPage,
                'page' => $page,
                'currentPage' => $productModel->pager->getCurrentPage(),
                'pageCount' => $productModel->pager->getPageCount(),
                'total' => $productModel->pager->getTotal(),
                'products' => $products,

            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }




    public function search()
    {
        $productModel = new ProductModel();

        $products = $productModel->select('products.*, categories.category_name')
            ->join('categories', 'categories.category_id = products.product_category_id')
            ->where('product_is_active', 1);

        //getParam
        $search = esc($this->request->getGet('search'));
        $product_name = esc($this->request->getGet('product_name'));
        $category_name = esc($this->request->getGet('category_name'));
        $product_sku = esc($this->request->getGet('product_sku'));

        if ($search) {
            $products
                ->like('product_name', $search)
                ->orLike('category_name', $search)
                ->orLike('product_sku', $search);
        } else {

            if ($product_name) {
                $products->like('product_name', $product_name);
            }

            if ($category_name) {
                $products->like('category_name', $category_name);
            }

            if ($product_sku) {
                $products->like('product_sku', $product_sku);
            }
        }

        $limit = esc($this->request->getGet('limit'));
        $limit = $limit ? intval($limit) : null;


        if ($limit) {
            $products->limit($limit);
        }
        $products = $products->findAll();

        if (!$products || count($products) == 0) {
            return $this->response
                ->setJSON([
                    'message' => 'No products found',
                ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response
            ->setJSON(
                $products,
            )
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function getMenuProducts()
    {
        $productModel = new ProductModel();

        $products = $productModel->select('products.*, categories.category_name')
            ->join('categories', 'categories.category_id = products.product_category_id')
            ->where('product_show_in_menu', 1)
            ->where('product_is_active', 1)
            ->findAll();

        return $this->response
            ->setJSON(
                $products,
            )
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function getWithSKU($sku)
    {
        $productModel = new ProductModel();

        $product = $productModel->select('products.*, categories.category_name')
            ->where('product_sku', $sku)
            ->join('categories', 'categories.category_id = products.product_category_id')
            ->first();

        if ($product) {
            return $this->response
                ->setJSON(
                    $product,
                )
                ->setStatusCode(ResponseInterface::HTTP_OK);
        } else {
            return $this->response
                ->setJSON([
                    'message' => 'Product not found',
                ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }
    }

    public function create()
    {
        $role = $this->user->role;

        if ($role != 'admin' && $role != 'manager') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to create product',
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }


        try {

            $productModel = new ProductModel();
            $productEntity = new ProductEntity();

            $product = $productEntity->fill($this->request->getPost());

            //Parse date production_date

            if ($productModel->save($product)) {
                return $this->response
                    ->setJSON([
                        'message' => 'Product created',
                        'product' => $product,
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_CREATED);
            } else {
                return $this->response
                    ->setJSON([
                        'message' => 'Failed to create product',
                        'errors' => $productModel->errors(),
                        'product' => $product,
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
        } catch (DatabaseException $e) {
            if ($e->getCode() == 1452) {
                return $this->response
                    ->setJSON([
                        'message' => 'Failed to created product',
                        'errors' => 'Category not found',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->response
                ->setJSON([
                    'message' => 'Failed to create product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {

            return $this->response
                ->setJSON([
                    'message' => 'Failed to create product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($sku)
    {
        try {

            $role = $this->user->role;

            if ($role != 'admin' && $role != 'manager') {
                return $this->response
                    ->setJSON([
                        'message' => 'Unauthorized to update product',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }


            $productModel = new ProductModel();
            $productEntity = new ProductEntity();

            $oldProduct = $productModel->where('product_sku', $sku)->first();

            if (!$oldProduct) {
                return $this->response
                    ->setJSON([
                        'message' => 'Product not found',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }

            $newProduct = $productEntity->fill($this->request->getPost());


            //Check if sku and slug hasn't changed
            if ($oldProduct->product_sku === $newProduct->product_sku) {
                //Unset sku
                unset($newProduct->product_sku);
            }

            if ($oldProduct->product_slug === $newProduct->product_slug) {
                //Unset slug
                unset($newProduct->product_slug);
            }



            if ($productModel->update($oldProduct->product_id, $newProduct)) {
                return $this->response
                    ->setJSON([
                        'message' => 'Product updated',
                        'product' => $newProduct,
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_OK);
            } else {
                return $this->response
                    ->setJSON([
                        'message' => 'Failed to update product',
                        'errors' => $productModel->errors(),
                        'product' => $newProduct,
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
        } catch (DatabaseException $e) {

            if ($e->getCode() == 1452) {
                return $this->response
                    ->setJSON([
                        'message' => 'Failed to update product',
                        'errors' => 'Category not found',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->response
                ->setJSON([
                    'message' => 'Failed to update product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response
                ->setJSON([
                    'message' => 'Failed to update product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function delete($sku)
    {
        try {

            $role = $this->user->role;

            if ($role != 'admin') {
                return $this->response
                    ->setJSON([
                        'message' => 'Unauthorized to delete product',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }

            $productModel = new ProductModel();

            $product = $productModel->where('product_sku', $sku)->first();

            if (!$product) {
                return $this->response
                    ->setJSON([
                        'message' => 'Product not found',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }

            if ($productModel->delete($product->product_id)) {
                return $this->response
                    ->setJSON([
                        'message' => 'Product deleted',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_OK);
            } else {
                return $this->response
                    ->setJSON([
                        'message' => 'Failed to delete product',
                        'errors' => $productModel->errors(),
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }
        } catch (DatabaseException $e) {


            return $this->response
                ->setJSON([
                    'message' => 'Failed to delete product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            return $this->response
                ->setJSON([
                    'message' => 'Failed to delete product',
                    'errors' => $e->getMessage(),
                ])
                ->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
