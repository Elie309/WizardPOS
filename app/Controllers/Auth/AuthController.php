<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Entities\Settings\EmployeeEntity;
use App\Helpers\JWTHelper;
use App\Models\Settings\EmployeeModel;

use CodeIgniter\HTTP\ResponseInterface;


class AuthController extends BaseController
{
    public function login()
    {
        $employeeEntity = new EmployeeEntity();
        $employeeEntity->fill($this->request->getPost());


        if (
            !($employeeEntity->employee_email || $employeeEntity->employee_phone_number) || !$employeeEntity->employee_password
        ) {
            return $this->response
                ->setJSON([
                    'message' => 'Email or phone number and password are required',
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $employeeModel = new EmployeeModel();
        $employee = null;


        if ($employeeEntity->employee_email) {
            $employee = $employeeModel->where('employee_email', $employeeEntity->employee_email)->where('employee_is_active', 1)->first();
        } else {
            $employee = $employeeModel->where('employee_phone_number', $employeeEntity->employee_phone_number)->where('employee_is_active', 1)->first();
        }

        if ($employee) {
            if (password_verify($employeeEntity->employee_password, $employee->employee_password)) {

                $token = JWTHelper::encodeUser(
                    $employee->employee_email,
                    $employee->employee_role,
                    $employee->employee_first_name . ' ' . $employee->employee_last_name
                );

                return $this->response
                    ->setJSON([
                        'message' => 'Employee logged in',
                        'token' => $token,
                        'user' => [
                            'name' => $employee->employee_first_name . ' ' . $employee->employee_last_name,
                            'email' => $employee->employee_email,
                            'role' => $employee->employee_role,
                        ]
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_OK);
            } else {

                return $this->response
                    ->setJSON([
                        'message' => 'Wrong credentials',
                    ])
                    ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
        } else {
            return $this->response
                ->setJSON([
                    'message' => 'Wrong credentials',
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }


    public function logout()
    {

        //Destroy the token
        // TODO: Implement token blacklisting

        return $this->response
            ->setContentType('text/json')
            ->setJSON([
                'message' => 'Employee logged out',
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function register()
    {

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to register employee',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $employeeEntity = new EmployeeEntity();
        $employeeEntity->fill($this->request->getPost());

        if(!$employeeEntity->employee_password || trim($employeeEntity->employee_password) === ''){
            return $this->response
                ->setJSON([
                    'message' => 'Password is required',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $employeeModel = new EmployeeModel();


        $employeeEntity->setPassword($employeeEntity->employee_password);

        if (!$employeeModel->save($employeeEntity)) {

            return $this->response
                ->setJSON([
                    'message' => 'Employee not registered',
                    'errors' => $employeeModel->errors(),
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $employee = $employeeModel->find($employeeModel->insertID());
        unset($employee->employee_password);

        return $this->response
            ->setJSON([
                'message' => 'Employee registered',
                'errors' => null,
                'data' => $employee
            ])
            ->setStatusCode(ResponseInterface::HTTP_CREATED);
    }

    public function getById($id)
    {

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to get employee',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if (!$employee) {
            return $this->response
                ->setJSON([
                    'message' => 'Employee not found',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        //unset password
        unset($employee->employee_password);

        return $this->response
            ->setJSON([
                'message' => 'Employee retrieved',
                'errors' => null,
                'data' => $employee
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function getAll()
    {

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to get employees',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $employeeModel = new EmployeeModel();
        $employees = $employeeModel->findAll();

        //unset password
        foreach ($employees as $employee) {
            unset($employee->employee_password);
        }

        return $this->response
            ->setJSON([
                'message' => 'Employees retrieved',
                'errors' => null,
                'data' => $employees
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function getAuthenticatedUser()
    {

        return $this->response
            ->setJSON([
                'message' => 'Employee logged in',
                'user' => [
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'role' => $this->user->role,
                ]
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }


    public function update($id)
    {
        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to update employee',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $employeeEntity = new EmployeeEntity();
        $employeeEntity->fill($this->request->getPost());

        $employeeModel = new EmployeeModel();
        $oldEmployee = $employeeModel->find($id);

        if (!$oldEmployee) {
            return $this->response->setJSON([
                'message' => 'Employee not found',
                'errors' => $employeeModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        //if password is set
        if ($employeeEntity->employee_password) {
            //verify password
            if ($employeeEntity->verifyPassword($oldEmployee->employee_password)) {
                unset($employeeEntity->employee_password);
            } else {
                $employeeEntity->setPassword($employeeEntity->employee_password);
            }
        }

        //CHeck emails if similar then unset
        if($oldEmployee->employee_email == $employeeEntity->employee_email){
            unset($employeeEntity->employee_email);
        }

        //Check phone numbers if similar then unset
        if($oldEmployee->employee_phone_number === $employeeEntity->employee_phone_number){
            unset($employeeEntity->employee_phone_number);
        }


        if (!$employeeModel->update($id, $employeeEntity)) {
            return $this->response->setJSON([
                'message' => 'Employee not updated',
                'errors' => $employeeModel->errors(),
                'data' => null
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        $employee = $employeeModel->find($id);

        unset($employee->employee_password);


        return $this->response->setJSON([
            'data' => $employee,
            'message' => 'Employee updated',
            'errors' => null
        ])->setStatusCode(ResponseInterface::HTTP_OK);
    }


    public function delete($id)
    {

        $role = $this->user->role;

        if ($role != 'admin') {
            return $this->response
                ->setJSON([
                    'message' => 'Unauthorized to delete employee',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->find($id);

        if (!$employee) {
            return $this->response
                ->setJSON([
                    'message' => 'Employee not found',
                    'errors' => null,
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_NOT_FOUND);
        }

        if (!$employeeModel->delete($id)) {
            return $this->response
                ->setJSON([
                    'message' => 'Employee not deleted',
                    'errors' => $employeeModel->errors(),
                    'data' => null
                ])
                ->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        return $this->response
            ->setJSON([
                'message' => 'Employee deleted',
                'errors' => null,
                'data' => null
            ])
            ->setStatusCode(ResponseInterface::HTTP_OK);
    }

    public function unauthorized()
    {
        return $this->response
            ->setJSON([
                'message' => 'Unauthorized',
            ])
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }
}
