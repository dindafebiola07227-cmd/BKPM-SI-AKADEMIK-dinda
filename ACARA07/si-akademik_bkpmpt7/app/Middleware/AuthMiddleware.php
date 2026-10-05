<?php

namespace App\Middleware;

class AuthMiddleware
{
    public function handle()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /SI-AKADEMIK_BKPMPT7/public/login');
            exit;
        }

        return true;
    }
}
