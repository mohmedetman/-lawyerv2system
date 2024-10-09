<?php

namespace App\Helpers;

use Laravel\Sanctum\PersonalAccessToken;

trait TokenType
{
    public  static  function generateToken() {
        $token = request()->bearerToken();
        $personal_token = PersonalAccessToken::find($token)->tokenable_type;
        return str_contains(strtolower($personal_token), 'lawyer') ?'lawyer':'employee' ;
    }

}
