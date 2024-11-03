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
            $employee = $employeeModel->where('employee_email', $employeeEntity->employee_email)->first();
        } else {
            $employee = $employeeModel->where('employee_phone_number', $employeeEntity->employee_phone_number)->first();
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

    public function unauthorized()
    {
        return $this->response
            ->setJSON([
                'message' => 'Unauthorized',
            ])
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }
}
