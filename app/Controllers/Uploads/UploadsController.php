<?php

namespace App\Controllers\Uploads;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class UploadsController extends BaseController
{

    public function upload()
    {

        $file = $this->request->getFile('file');

        if($file === null){
            return $this->response->setJSON([
                'message' => 'No file uploaded'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        //Check if the file is valid
        if (!$file->isValid()) {
            return $this->response->setJSON([
                'message' => 'Invalid file'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        //Check if the file is an image
        $type = $file->getMimeType();
        if (strpos($type, 'image') === false) {
            return $this->response->setJSON([
                'message' => 'Invalid file type'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        //Check size 640x480
        $size = getimagesize($file);
        if($size[0] > 640 || $size[1] > 480){
            return $this->response->setJSON([
                'message' => 'Image size must be 640x480'
            ])->setStatusCode(ResponseInterface::HTTP_BAD_REQUEST);
        }

        if($type !== 'image/webp'){
            \Config\Services::image()
                ->withFile($file)
                ->convert(IMAGETYPE_WEBP)
                ->save();
        }

        //rename the file
        $newName = $file->getRandomName();
        $file->move(FCPATH . 'uploads', $newName);

        //get URL of the file
        $url = base_url('uploads/' . $newName);


        return $this->response->setJSON([
            'message' => 'File uploaded successfully',
            'url' => $url
        ])->setStatusCode(ResponseInterface::HTTP_CREATED);

    }
        
}
