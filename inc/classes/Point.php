<?php

require_once('utils/database.php');

/**
 * Point
 * Point dans une carte
 */
class Point
{
    private static $tableName = "points";

    /**
     * L'identifiant du Point
     * @var int
     */
    public $idPoint;
  
    /**
     * Latitude du point
     * @var double
     */
    public $lat;

    /**
     * Longitude du point
     * @var double
     */
    public $lon;

    /**
     * Nom du point
     * @var string
     */
    public $name;

    /**
     * Date d'ajout du point
     * @var int
     */
    public $dateAjout;

    /**
     * Code postal du point
     * @var int
     */
    public $codePostal;

    /**
     * Lien lié au point
     * @var string
     */
    public $link;

    /**
     * Description du point
     * Ne doit pas servir à remplacer les autres champs !
     * @var string
     */
    public $description;

    /**
     * Constructeur du Point
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        if (!empty($row)) {
            $this->idPoint = $row['idPoint'] ?? null;
            $this->lat = $row['lat'] ?? null;
            $this->lon = $row['lon'] ?? null;
            $this->name = $row['name'] ?? null;
            $this->dateAjout = $row['dateAjout'] ?? null;
            $this->codePostal = $row['codePostal'] ?? null;
            $this->link = $row['link'] ?? null;
            $this->description = $row['description'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return [
            'idPoint' => $this->idPoint,
            'lat' => $this->lat,
            'lon' => $this->lon,
            'name' => $this->name,
            'dateAjout' => $this->dateAjout,
            'codePostal' => $this->codePostal,
            'link' => $this->link,
            'description' => $this->description
        ];
    }
    
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?Point {
        if (empty($row)) {
            return null;
        } else {
            return new self($row);
        }
    }

    /**
     * Sauvegarde du point en BDD
     * @return void
     */
    public function save() {

        if ($this->idPoint) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET lat = ?, lon = ?, name = ?, dateAjout = ?, codePostal = ?, link = ?, description = ? WHERE idPoint = ?"); 
            $req->execute(array(
                $this->lat,
                $this->lon,
                $this->name,
                $this->dateAjout,
                $this->codePostal,
                $this->link,
                $this->description,
                $this->idPoint
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(`lat`, `lon`, `name`, `dateAjout`, `codePostal`, `link`, `description`) VALUES(?, ?, ?, ?, ?, ?, ?)"); 
            $req->execute(array(
                $this->lat,
                $this->lon,
                $this->name,
                $this->dateAjout,
                $this->codePostal,
                $this->link,
                $this->description
            ));

            $this->idPoint = Database::db()->lastInsertId();
        }
    }
    
    /**
     * Méthode de suppression de la carte en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE idPoint = ?"); 
        $req->execute(array($this->idPoint));
    }
      
    /**
     * Récupération d'un groupe en BDD
     * @return Point
     */
    public static function find(int $id) : ?Point {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE idGroup = ?");
        $req->execute(array($id));

        return $req->fetch();
    }

}
