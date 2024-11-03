<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Entities\Settings\EmployeeEntity;
use App\Models\Settings\EmployeeModel;
use CodeIgniter\HTTP\ResponseInterface;
use Firebase\JWT\JWT;

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

                $key = getenv('JWT_SECRET');
                $iat = time();
                $exp = $iat + 3600;

                $payload = array(
                    "iss" => "user_wizardpos", 
                    "aud" => "user_wizardpos", 
                    "sub" => "auth", 
                    "iat" => $iat, 
                    "exp" => $exp, 
                    "email" => $employee->employee_email,
                    'role' => $employee->employee_role,
                );

                $token = JWT::encode($payload, $key, 'HS256');

                return $this->response
                    ->setJSON([
                        'message' => 'Employee logged in',
                        'token' => $token,
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

    public function unauthorized()
    {
        return $this->response
            ->setJSON([
                'message' => 'Unauthorized',
            ])
            ->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }
}
