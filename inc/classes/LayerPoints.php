<?php

require_once('utils/database.php');

/**
 * LayerPoints
 * Classe d'association entre une couche 
 * et ses différents points
 */
class LayerPoints
{
    private static $tableName = "layer_point";

    /**
     * L'identifiant du carte
     * @var int
     */
    public $idLayerPoints;
  
    /**
     * Identifiant du point
     * @var int
     */
    public $point;
  
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
     * Constructeur du LayerPoints
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        if (!empty($row)) {
            $this->idLayerPoints = $row['idLayerPoints'] ?? null;
            $this->point = $row['point'] ?? null;
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
            'idLayerPoints' => $this->idLayerPoints,
            'point' => $this->point,
            'layer' => $this->layer,
            'actif' => $this->actif
        ];
    }
    
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?LayerPoints {
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

        if ($this->idLayerPoints) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET point = ?, layer = ?, actif = ? WHERE idLayerPoints = ?"); 
            $req->execute(array(
                $this->point,
                $this->layer,
                $this->actif,
                $this->idLayerPoints
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(`point`, `layer`, `actif`) VALUES(?, ?, ?)"); 
            $req->execute(array(
                $this->point,
                $this->layer,
                $this->actif
            ));

            $this->idLayerPoints = Database::db()->lastInsertId();
        }
    }
    
    /**
     * Méthode de suppression de la carte en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE idLayerPoints = ?"); 
        $req->execute(array($this->idLayerPoints));
    }
        
    /**
     * Récupération d'une carte en BDD
     * @return LayerPoints
     */
    public static function find(int $id) : ?Event {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE idLayerPoints = ?");
        $req->execute(array($id));

        return self::rowToObject($req->fetch());
    }

    /**
     * Récupération des points d'une carte
     * @return array
     */
    public static function findPoints(int $idLayer) : ?array {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE layer = ?");
        $req->execute(array($idLayer));
        return $req->fetchAll();
    }
}
