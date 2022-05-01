<?php
namespace Groupe;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Groupe {
    public $gid;
    public $nom;
    public $devise;
    public $createur;
}

class GroupeRepository {
    const TABLE_NAME = 'Groupe';

    public function storeGroupe($groupe, &$message) {
        $noError = false;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME." (nom, devise, uid) VALUES (:nom, :devise, :uid)");
            $stmt->bindValue(':nom', $groupe->nom);
            $stmt->bindValue(':devise', $groupe->devise);
            $stmt->bindValue(':uid', $groupe->createur);
            if ($stmt->execute()){
                $message .= 'Le groupe a bien été créé' ;
                $groupe->gid = $bdd->lastInsertId();
                $noError = true;
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch(PDOException $e) {
            $message .= $e->getMessage().'<br>';
        }

        DBLink::disconnect($bdd);
        return $noError;
    }

    public function showGroups() {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $result = $bdd->query("SELECT * FROM " . self::TABLE_NAME);
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function showGroupById($gid) {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE gid = :gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("Groupe\Groupe");
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur: ' . $stmt->errorCode() . ')<br>';
            }
            $stmt = null;
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function updateGroup($gid, $group, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET nom =:nom, devise =:devise WHERE gid =:gid");
            $stmt->bindValue(':nom', $group->nom);
            $stmt->bindValue(':devise', $group->devise);
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()) {
                $noError = true;
                $message = "Le groupe a bien été modifié";
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }
}