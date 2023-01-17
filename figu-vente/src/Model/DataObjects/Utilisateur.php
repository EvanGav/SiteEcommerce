<?php

namespace App\Model\DataObjects;

use App\Lib\Hash;

abstract class Utilisateur
{
    private string $nom;
    private string $motDePasse;
    private string $email;
    private string $emailAValider;
    private string $nonce;

    /**
     * @param String $nom
     * @param String $motDePasse
     */
    public function __construct(string $nom, string $motDePasse,string $email, string $emailAValider, string $nonce)
    {
        $this->nom = htmlspecialchars($nom);
        $this->motDePasse = $motDePasse;
        if($emailAValider!=""){
            $this->email="";
        }
        else{
            $this->email=$email;
        }
        $this->emailAValider=htmlspecialchars($emailAValider);
        $this->nonce=htmlspecialchars($nonce);
        $id="";
    }

    public function getId(){}

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return String
     */
    public function getNom(): string
    {
        return $this->nom;
    }

    /**
     * @return String
     */
    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    /**
     * @param String $motDePasse
     */
    public function setMotDePasse(string $motDePasse): void
    {
        $this->motDePasse = $motDePasse;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getEmailAValider(): string
    {
        return $this->emailAValider;
    }

    /**
     * @return string
     */
    public function getNonce(): string
    {
        return $this->nonce;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @param string $emailAValider
     */
    public function setEmailAValider(string $emailAValider): void
    {
        $this->emailAValider = $emailAValider;
    }

    /**
     * @param string $nonce
     */
    public function setNonce(string $nonce): void
    {
        $this->nonce = $nonce;
    }





}