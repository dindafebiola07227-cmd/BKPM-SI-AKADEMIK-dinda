<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /SI-AKADEMIK_BKPMPT6/public/login');
            exit;
        }

        return true;
    }
}
