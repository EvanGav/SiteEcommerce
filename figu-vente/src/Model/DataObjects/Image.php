<?php

namespace App\Model\DataObjects;

use App\Model\Repository\DatabaseConnection;

class Image
{
    private string $type;
    private $data;
    private Produit $produit;
    private string $id;
    private int $taille;
    private string $nom;
    private string $path;

    public function __construct(string $nom, int $taille,string $imageType, string $imageData, Produit $produit)
    {
        $this->nom=$nom;
        $this->taille=$taille;
        $this->type = $imageType;
        $this->data = $imageData;
        $this->produit = $produit;
        $this->id = $this->constructId();
    }

    /**
     * @return string
     */
    public function getType(): string{
        return $this->type;
    }

    /**
     * @return string
     */
    public function getData(){
        return $this->data;
    }

    /**
     * @return Produit
     */
    public function getProduit(): Produit{
        return $this->produit;
    }

    private function getIdPrec(){
        $pdoStatement=DatabaseConnection::getPdo()->prepare("SELECT MAX(idImage) FROM p_images");
        $pdoStatement->execute();
        return $pdoStatement->fetch();
    }

    private function constructId(){
        $nb=intval(substr($this->getIdPrec()[0],1))+1;
        return "I".$nb;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getTaille(): int
    {
        return $this->taille;
    }

    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }/**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath(string $path): void
    {
        $this->path = $path;
    }






}