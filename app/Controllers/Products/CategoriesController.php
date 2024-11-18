<?php

namespace App\Controllers\Products;

use App\Controllers\BaseController;
use App\Entities\Products\CategoryEntity;
use App\Models\Products\CategoryModel;
use CodeIgniter\Database\Exceptions\DatabaseException;
use CodeIgniter\HTTP\ResponseInterface;

class CategoriesController extends BaseController
{
    public function index()
    {
        $categoryModel = new CategoryModel();

        $data = $categoryModel->findAll();

        return $this->response->setJSON($data)->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function show($category_name)
    {
        $categoryModel = new CategoryModel();


        $category = $categoryModel->where('category_name', $category_name)->first();

        if ($category === null) {
            return $this->response->setJSON(['message' => 'Category not found'])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        return $this->response->setJSON($category)->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function create()
    {
        $categoryModel = new CategoryModel();
        $categoriesEntity = new CategoryEntity();

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to create category',
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }


        $categoriesEntity->fill($this->request->getPost());

        if ($categoryModel->save($categoriesEntity)) {
            return $this->response->setJSON([
                'message' => 'Category created',
                'category' => $categoriesEntity

            ])->setStatusCode(ResponseInterface::HTTP_CREATED);
        }

        return $this->response->setJSON([
            'message' => 'Category not saved',
            'errors' => $categoryModel->errors(),
        ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }

    public function update($category_name)
    {
        $categoryModel = new CategoryModel();
        $categoriesEntity = new CategoryEntity();

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to update category',
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $category = $categoryModel->where('category_name', $category_name)->first();

        if (!$category) {
            return $this->response->setJSON(['message' => 'Category not found'])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        $categoriesEntity->fill($this->request->getPost());

        if ($category->category_name === $categoriesEntity->category_name) {
            unset($categoriesEntity->category_name);
        }

        if ($categoryModel->update($category->category_id, $categoriesEntity)) {
            return $this->response->setJSON([
                'message' => 'Category updated',
                'category' => $categoriesEntity
            ])->setStatusCode(ResponseInterface::HTTP_OK);
        }

        return $this->response->setJSON([
            'message' => 'Category not updated',
            'errors' => $categoryModel->errors(),
        ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
    }

    public function delete($id)
    {

        try {

            $categoryModel = new CategoryModel();

            $role = $this->user->role;

            if ($role != 'admin') {
                return $this->response
                    ->setJSON([
                        'message' => 'Unauthorized to delete category',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }

            if (!$categoryModel->find($id)) {
                return $this->response->setJSON(['message' => 'Category not found'])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
            }

            if ($categoryModel->delete($id)) {
                return $this->response->setJSON(['message' => 'Category deleted'])->setStatusCode(ResponseInterface::HTTP_OK);
            }

            return $this->response->setJSON(['message' => 'Category not deleted'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        } catch (DatabaseException $e) {
            //Foreign key constraint
            if ($e->getCode() == 1451) {
                return $this->response->setJSON(['message' => 'Category cannot be deleted because it is being used'])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
            }

            return $this->response->setJSON(['message' => $e->getMessage()])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        } catch (\Exception $e) {
            return $this->response->setJSON(['message' => $e->getMessage()])->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
