<?php

require_once('utils/database.php');

/**
 * Event
 * Evènement dans une carte
 */
class Event extends Point
{
    private static $tableNameEvent = "events";

    /**
     * Date et heure de l'évènement
     * @var int
     */
    public $datetime;

    /**
     * placeInfo
     * "En face de la mairie..."
     * @var string
     */
    public $placeinfo;

    /**
     * Identifiant de l'organisation associée
     * @var int
     */
    public $organizer;

    /**
     * Nombre de participant.e.s
     * @var int
     */
    public $nbParticipants;


    /**
     * Constructeur de l'évènement
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        parent::__construct($row);

        if (!empty($row)) {
            $this->datetime = $row['datetime'] ?? null;
            $this->placeinfo = $row['placeinfo'] ?? null;
            $this->organizer = $row['organizer'] ?? null;
            $this->nbParticipants = $row['nbParticipants'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return array_merge(parent::toClient(), [
            'datetime' => $this->datetime,
            'placeinfo' => $this->placeinfo,
            'organizer' => $this->organizer,
            'nbParticipants' => $this->nbParticipants
        ]);
    }
    
    /**
     * Sauvegarde de l'évènement en BDD avec enregistreData
     * @return void
     */
    public function save() {

        parent::save();

        if ($this->idPoint) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableNameEvent ." SET 
                datetime = ?,
                placeinfo = ?,
                organizer = ?,
                nbParticipants = ?,
                
                WHERE lePoint = ?"); 
            $req->execute(array(
                $this->datetime,
                $this->placeinfo,
                $this->organizer,
                $this->nbParticipants,
                $this->idPoint
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableNameEvent ."(
                `datetime`,
                `placeinfo`,
                `organizer`,
                `nbParticipants`
                `lePoint`) VALUES(?, ?, ?, ?, ?)"); 
            $req->execute(array(
                $this->datetime,
                $this->placeinfo,
                $this->organizer,
                $this->nbParticipants,
                $this->idPoint
            ));

            $this->idPoint = Database::db()->lastInsertId();
        }

    }
    
    /**
     * Méthode de suppression de l'évènement en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableNameEvent ." WHERE lePoint = ?"); 
        $req->execute(array($this->idPoint));

        parent::delete();
    }
        
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?Event {
        if (empty($row)) {
            return null;
        } else {
            return new self($row);
        }
    }

    /**
     * Récupération d'un évènement en BDD
     * @return Event
     */
    public static function find(int $id) : ?Event {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableNameEvent ." WHERE lePoint = ?");
        $req->execute(array($id));

        return self::rowToObject(array_merge($req->fetch(), parent::find($id)->toClient()));

    }

}
