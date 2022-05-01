<?php
namespace Participer;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Participer {
    public $uid;
    public $gid;
    public $estConfirme;
}

class ParticiperRepository {
    const TABLE_NAME = 'Participer';

    public function setParticipe($participe, &$message) {
        $noError = false;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME." (uid, gid, estConfirme) VALUES (:uid, :gid, :estConfirme)");
            $stmt->bindValue(':uid', $participe->uid);
            $stmt->bindValue(':gid', $participe->gid);
            $stmt->bindValue(':estConfirme', $participe->estConfirme);
            if ($stmt->execute()){
                $noError = true;
            }
            $stmt = null;
        } catch(PDOException $e) {
            $message = $e->getMessage();
        }

        DBLink::disconnect($bdd);
        return $noError;
    }

    public function confirmerParticipe($uid, $gid, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET estConfirme = 1  WHERE uid =:uid AND gid=:gid");
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()) {
                $noError = true;
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function getParticipeByUserId($uid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE uid = :uid");
            $stmt->bindValue(':uid', $uid);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Participer\Participer");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function getConfirmeByUidAndGid($uid, $gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE uid = :uid && gid = :gid");
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetchObject("Participer\Participer");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function supprimerInvite($uid, $gid, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE uid = :uid AND gid=:gid");
            $stmt->bindValue(':uid', $uid);
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $message = "L'invitation a bien été supprimée";
                $noError = true;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

}