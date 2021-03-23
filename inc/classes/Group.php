<?php

require_once('utils/database.php');

/**
 * Group
 * Groupe de points sur une carte. Cela peut-être une liste
 * de groupes locaux ou d'évènements... Il peut constituer
 * une couche sur une carte.
 */
class Group
{
    private static $tableName = "groups";

    /**
     * L'identifiant du groupe
     * @var int
     */
    public $idGroup;
  
    /**
     * Nom du groupe
     * @var string
     */
    public $name;
  
    /**
     * Icône du groupe (optionnel)
     * @var string
     */
    public $icon;

    /**
     * Constructeur du Group
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        if (!empty($row)) {
            $this->idGroup = $row['idGroup'] ?? null;
            $this->name = $row['name'] ?? null;
            $this->icon = $row['icon'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return [
            'idGroup' => $this->idGroup,
            'name' => $this->name,
            'icon' => $this->icon
        ];
    }
    
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?Group {
        if (empty($row)) {
            return null;
        } else {
            return new self($row);
        }
    }
 
    /**
     * Sauvegarde du groupe en BDD
     * @return void
     */
    public function save() {

        if ($this->idGroup) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET name = ?, icon = ? WHERE idGroup = ?"); 
            $req->execute(array(
                $this->name,
                $this->icon,
                $this->idGroup
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(`name`, `icon`) VALUES(?, ?)"); 
            $req->execute(array(
                $this->name,
                $this->icon
            ));

            $this->idGroup = Database::db()->lastInsertId();
        }
    }
    
    /**
     * Méthode de suppression du groupe en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE idGroup = ?"); 
        $req->execute(array($this->idGroup));
    }
        
    /**
     * Récupération d'un groupe en BDD
     * @return Group
     */
    public static function find(int $id) : ?Event {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE idGroup = ?");
        $req->execute(array($id));

        return self::rowToObject($req->fetch());
    }

}
