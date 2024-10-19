<?php

namespace App\Controllers;

class MainController extends BaseController
{
    public function index()
    {
        return $this->response->setJSON([
            'message' => 'Hello World'
        ]);
    }
}
