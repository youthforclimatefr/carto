<?php

require_once('utils/database.php');

/**
 * Map
 * Carte contenant des groupes de points
 */
class Map
{
    private static $tableName = "maps";

    /**
     * L'identifiant du carte
     * @var int
     */
    public $idMap;
  
    /**
     * Nom du carte
     * @var string
     */
    public $name;

    /**
     * Constructeur du Map
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        if (!empty($row)) {
            $this->idMap = $row['idMap'] ?? null;
            $this->name = $row['name'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return [
            'idMap' => $this->idMap,
            'name' => $this->name
        ];
    }
    
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?Map {
        if (empty($row)) {
            return null;
        } else {
            return new self($row);
        }
    }
 
    /**
     * Sauvegarde du carte en BDD
     * @return void
     */
    public function save() {

        if ($this->idMap) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET name = ? WHERE idMap = ?"); 
            $req->execute(array(
                $this->name,
                $this->idMap
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(`name`) VALUES(?)"); 
            $req->execute(array(
                $this->name
            ));

            $this->idMap = Database::db()->lastInsertId();
        }
    }
    
    /**
     * Méthode de suppression de la carte en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE idMap = ?"); 
        $req->execute(array($this->idMap));
    }
        
    /**
     * Récupération d'une carte en BDD
     * @return Map
     */
    public static function find(int $id) : ?Event {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE idMap = ?");
        $req->execute(array($id));

        return self::rowToObject($req->fetch());
    }

}
