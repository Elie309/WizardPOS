<?php

namespace App\Filters;

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
        $key = getenv('JWT_SECRET');
        $header = $request->header("Authorization");
        $token = null;

        $currentUri = strtolower(uri_string());

        if (!(str_contains($currentUri, "login") || str_contains($currentUri, "unauthorized")
            || str_contains($currentUri, "logout"))) {

            // extract the token from the header
            if (!empty($header)) {
                if (preg_match('/Bearer\s(\S+)/', $header, $matches)) {
                    $token = $matches[1];
                }
            }

            if (is_null($token) || empty($token)) {
                return redirect()->to('/api/auth/unauthorized');
            }

            try {
                // $decoded = JWT::decode($token, $key, array("HS256"));
                $decoded = JWT::decode($token, new Key($key, 'HS256'));

                // check if the token is expired
                if ($decoded->exp < time()) {
                    return redirect()->to('/api/auth/unauthorized');
                }

                // check if the token is valid
                if ($decoded->iss !== "user_wizardpos" || $decoded->aud !== "user_wizardpos" || $decoded->sub !== "auth") {
                    return redirect()->to('/api/auth/unauthorized');
                }
                // check if the token is valid
                if (!isset($decoded->email) || !isset($decoded->role)) {
                    return redirect()->to('/api/auth/unauthorized');
                }
                // set the email and role in the request
                $request->email = $decoded->email;
                $request->role = $decoded->role;
            } catch (Exception $ex) {
                return redirect()->to('/api/auth/unauthorized');
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
