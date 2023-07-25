<?php

namespace Model;

abstract class Connect
{
    // Informations de connection
    const HOST = "localhost";
    const DB = "cinema";
    const USER = "root";
    const PASS = "";

    // Se connecte à la base de données
    public static function seConnecter()
    {
        try
        {
            return new \PDO("mysql:host=" . self::HOST . ";dbname=" . self::DB . ";charset=utf8", self::USER, self::PASS);
        }
        catch (\PDOException $ex)
        {
            return $ex->getMessage(); // Retourne un message d'erreur en cas de problème
        }
    }
}