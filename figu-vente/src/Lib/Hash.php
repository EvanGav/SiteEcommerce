<?php

namespace App\Lib;

class Hash
{

    public static function poivrer(string $mdp){
        return hash_hmac("sha256",$mdp,"M7UKGv9fkptxwbSmZvlr1U");
    }

    public static function hacher($mdp){
        return password_hash(self::poivrer($mdp), PASSWORD_DEFAULT);
    }

}