<?php

require_once('utils/database.php');

/**
 * Organization
 * Orga dans une carte
 */
class Organization extends Point
{
    private static $tableName = "organizations";
  
    /**
     * Adresse e-mail
     * @var string
     */
    public $email;
  
    /**
     * Compte Instagram
     * @var string
     */
    public $instagram;
  
    /**
     * Compte Facebook
     * @var string
     */
    public $facebook;
  
    /**
     * Compte Twitter
     * @var string
     */
    public $twitter;
  
    /**
     * Compte TikTok
     * @var string
     */
    public $tiktok;
  
    /**
     * Numéro de téléphone du contact presse
     * @var string
     */
    public $press_phone;
  
    /**
     * Nom du contact Presse
     * @var string
     */
    public $press_name;

    /**
     * Constructeur de l'organisation
     * @param  mixed $row
     * @return void
     */
    public function __construct($row = NULL) {

        parent::__construct($row);

        if (!empty($row)) {
            $this->email = $row['email'] ?? null;
            $this->instagram = $row['instagram'] ?? null;
            $this->facebook = $row['facebook'] ?? null;
            $this->twitter = $row['twitter'] ?? null;
            $this->tiktok = $row['tiktok'] ?? null;
            $this->press_phone = $row['press_phone'] ?? null;
            $this->press_name = $row['press_name'] ?? null;
        }
    }

    /**
     * Méthode de renvoi de l'objet en front sous la forme d'un tableau
     * @return array
     */
    public function toClient() {
        return array_merge(parent::toClient(), [
            'email' => $this->email,
            'instagram' => $this->instagram,
            'facebook' => $this->facebook,
            'twitter' => $this->twitter,
            'tiktok' => $this->tiktok,
            'press_phone' => $this->press_phone,
            'press_name' => $this->press_name
        ]);
    }
    
    /**
     * Sauvegarde de l'organisation en BDD avec enregistreData
     * @return void
     */
    public function save() {

        parent::save();

        if ($this->idPoint) {
            // L'identifiant est déjà initialisé : l'objet est déjà en BDD
            
            $req = Database::db()->prepare("UPDATE ". self::$tableName ." SET email = ?,
                instagram = ?,
                facebook = ?,
                twitter = ?,
                tiktok = ?,
                press_phone = ?,
                press_name = ? WHERE lePoint = ?"); 
            $req->execute(array(
                $this->email,
                $this->instagram,
                $this->facebook,
                $this->twitter,
                $this->tiktok,
                $this->press_phone,
                $this->press_name,
                $this->idPoint
            ));
            
        } else {
            // L'identifiant n'est pas encore défini, objet à insérer
            $req = Database::db()->prepare("INSERT INTO ". self::$tableName ."(
                `email`,
                `instagram`,
                `facebook`,
                `twitter`,
                `tiktok`,
                `press_phone`,
                `press_name`,
                `lePoint`) VALUES(?, ?, ?, ?, ?, ?, ?, ?)"); 
            $req->execute(array(
                $this->email,
                $this->instagram,
                $this->facebook,
                $this->twitter,
                $this->tiktok,
                $this->press_phone,
                $this->press_name,
                $this->idPoint
            ));

            $this->idPoint = Database::db()->lastInsertId();
        }

    }
    
    /**
     * Méthode de suppression de l'organisation en BDD
     * @return void
     */
    public function delete() {
        $req = Database::db()->prepare("DELETE FROM ". self::$tableName ." WHERE lePoint = ?"); 
        $req->execute(array($this->idPoint));

        parent::delete();
    }
        
    /**
     * Méthode de transformation d'un tableau contenant les valeurs en objet
     * @param  mixed $row
     * @return void
     */
    public static function rowToObject($row) : ?Organization {
        if (empty($row)) {
            return null;
        } else {
            return new self($row);
        }
    }

    /**
     * Récupération d'un organisation en BDD
     * @return Organization
     */
    public static function find(int $id) : ?Organization {
        $req = Database::db()->prepare("SELECT * FROM ". self::$tableName ." WHERE lePoint = ?");
        $req->execute(array($id));

        return self::rowToObject(array_merge($req->fetch(), parent::find($id)->toClient()));
    }

}
