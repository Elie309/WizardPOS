<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        log_message('info', 'Hello World');
        return $this->response->setJSON([
            'message' => 'Hello World'
        ]);
    }
}
