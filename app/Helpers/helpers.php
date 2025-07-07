<?php

use Illuminate\Support\Facades\Auth;

if (!function_exists('checkRole')) {
    function checkRole(...$roles)
    {
        $user = Auth::user();

        // Cek login dan token
        if (!$user || !$user->currentAccessToken()) {
            return false;
        }

        // Cocokkan token abilities
        foreach ($roles as $role) {
            if ($user->tokenCan($role)) {
                return true;
            }
        }

        return false;
    }
}

