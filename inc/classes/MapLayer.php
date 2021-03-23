<?php

require_once('utils/database.php');

/**
 * MapLayer
 * Classe d'association entre une carte 
 * et ses différentes couches de points
 */
class MapLayer
{
    private static $tableName = "map_layer";

    /**
     * L'identifiant du carte
     * @var int
     */
    public $idMapLayer;
  
    /**
     * Identifiant de la carte
     * @var int
     */
    public $map;
  
    /**
     * Identifiant de la couche
     * @var int
     */
    public $layer;
  
    /**
     * La couche est-elle activée ?
     * @var int
     */
    public $actif;

    /**
     * Constructeur du MapLayer
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        if (!empty($row)) {
            $this->idMapLayer = $row['idMapLayer'] ?? null;
            $this->map = $row['map'] ?? null;
            $this->layer = $row['layer'] ?? null;
            $this->actif = $row['actif'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return [
            'idMapLayer' => $this->idMapLayer,
            'map' => $this->map,
            'layer' => $this->layer,
            'actif' => $this->actif
        ];
    }
    
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?MapLayer {
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

        if ($this->idMapLayer) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET map = ?, layer = ?, actif = ? WHERE idMapLayer = ?"); 
            $req->execute(array(
                $this->map,
                $this->layer,
                $this->actif,
                $this->idMapLayer
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(`map`, `layer`, `actif`) VALUES(?, ?, ?)"); 
            $req->execute(array(
                $this->map,
                $this->layer,
                $this->actif
            ));

            $this->idMapLayer = Database::db()->lastInsertId();
        }
    }
    
    /**
     * Méthode de suppression de la carte en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE idMapLayer = ?"); 
        $req->execute(array($this->idMapLayer));
    }
        
    /**
     * Récupération d'une carte en BDD
     * @return MapLayer
     */
    public static function find(int $id) : ?Event {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE idMapLayer = ?");
        $req->execute(array($id));

        return self::rowToObject($req->fetch());
    }

    /**
     * Récupération des couches d'une carte
     * @return array
     */
    public static function findLayers(int $idMap) : ?array {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE map = ?");
        $req->execute(array($idMap));
        return $req->fetchAll();
    }

}
