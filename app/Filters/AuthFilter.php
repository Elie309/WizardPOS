<?php

namespace App\Filters;

use App\Helpers\JWTHelper;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return RequestInterface|ResponseInterface|string|void
     */
    public function before(RequestInterface $request, $arguments = null)
    {

        $currentUri = strtolower(uri_string());
        $response = service('response');

        if (!(str_contains($currentUri, "login") || str_contains($currentUri, "unauthorized")
            || str_contains($currentUri, "logout")) && $request->getMethod() !== 'OPTIONS') {

            try {

                $token = JWTHelper::removeBearer($request->header('Authorization'));

                if (is_null($token) || empty($token)) {
                    return $response->setJSON([
                        'message' => 'Unauthorized',
                    ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
                }

                $decoded = JWTHelper::decode($token);

                // check if the token is expired
                if ($decoded->exp < time()) {
                    return $response->setJSON([
                        'message' => 'Unauthorized',
                    ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
                }

                // check if the token is valid
                if ($decoded->iss !== "user_wizardpos" || $decoded->aud !== "user_wizardpos" || $decoded->sub !== "auth") {
                    return $response->setJSON([
                        'message' => 'Unauthorized',
                    ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
                }
                // check if the token is valid
                if (!isset($decoded->email) || !isset($decoded->role)) {
                    return $response->setJSON([
                        'message' => 'Unauthorized',
                    ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
                }
            } catch (Exception $ex) {
                return $response->setJSON([
                    'message' => 'Unauthorized',
                ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return ResponseInterface|void
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
