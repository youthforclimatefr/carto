<?php

/**
 * Connexion à la base de donnée MySQL
 * Utilise PDO et le pattern singleton.
 */
class Database
{

    /**
     * Objet PDO connecté à la BDD
     */
    public $dbCon;

    /**
     * Instance de la classe
     */
    private static $instance;

    /**
     * Constructeur de la connexion à la base de donnée
     * @param  mixed $row
     * @return void
     */
    private function __construct() {
        try {
            $this->dbCon = new \PDO(
                'mysql:host='. $GLOBALS['conf']['host'] .';dbname='. $GLOBALS['conf']['database'] .';charset=utf8',
                $GLOBALS['conf']['username'],
                $GLOBALS['conf']['password'],
                array(
                    \PDO::ATTR_EMULATE_PREPARES => false, 
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
                );
        } catch (\PDOException $e){
            echo $e->getMessage();
        }
    }

    /**
     * Retourne l'instance de la classe Database
     */
    public static function getInstance(): ?Database {
        if (self::$instance == null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    /**
     * Retourne l'instance de PDO
     */
    public static function db(): ?\PDO {
        return self::getInstance()->dbCon;
    }

}
