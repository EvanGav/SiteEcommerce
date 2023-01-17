<?php

namespace App\Lib;

class Cookie
{
    public static function enregistrer(string $cle, mixed $valeur, ?int $dureeExpiration = null): void
    {
        if (is_null($dureeExpiration)) {
            $dureeExpiration = 0;
        }
        setcookie($cle, serialize($valeur), $dureeExpiration);
    }

    public static function lire(string $cle): mixed{
        if (isset($_COOKIE[$cle])) {
            return unserialize($_COOKIE[$cle]);
        }
        return null;
    }

    public static function contient($cle) : bool{
        return isset($_COOKIE[$cle]);
    }

    public static function supprimer($cle) : void{
        unset($_COOKIE[$cle]);
    }
}