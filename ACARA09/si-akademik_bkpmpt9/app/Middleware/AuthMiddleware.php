<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {

            header(
                'Location: /si-akademik_bkpmpt9/public/login'
            );

            exit;
        }

        return true;
    }
}