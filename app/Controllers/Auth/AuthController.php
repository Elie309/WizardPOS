<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;
use App\Entities\Settings\EmployeeEntity;
use App\Models\Settings\EmployeeModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthController extends BaseController
{
    public function login()
    {
        $employeeEntity = new EmployeeEntity();
        $employeeEntity->fill($this->request->getPost());

        $employeeModel = new EmployeeModel();
        $employee = $employeeModel->where('employee_email', $employeeEntity->employee_email)->first();

        if ($employee) {
            if (password_verify($employeeEntity->employee_password, $employee->employee_password)) {
                $this->session->set('number', $employee->employee_number);
                $this->session->set('role', $employee->employee_role);
                $this->session->set('isLoggedIn', true);

                return $this->response
                    ->setJSON([
                        'message' => 'Employee logged in',
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

        $this->session->destroy();

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
