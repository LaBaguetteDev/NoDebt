<?php
namespace Depense;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Depense {
    public $did;
    public $date;
    public $montant;
    public $libelle;
    public $tag;
    public $gid;
    public $uid;
}

class DepenseRepository {
    const TABLE_NAME = 'Depense';

    public function storeDepense($depense, &$message) {
        $noError = false;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME." (date, montant, libelle, tag, gid, uid) VALUES (:date, :montant, :libelle, :tag, :gid, :uid)");
            $stmt->bindValue(':date', $depense->date);
            $stmt->bindValue(':montant', $depense->montant);
            $stmt->bindValue(':tag', $depense->tag);
            $stmt->bindValue(':libelle', $depense->libelle);
            $stmt->bindValue(':gid', $depense->gid);
            $stmt->bindValue(':uid', $depense->uid);

            if ($stmt->execute()){
                $message .= 'La dépense a bien été ajoutée' ;
                $depense->did = $bdd->lastInsertId();
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

    public function getDepenseById($did) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE did = :did");
            $stmt->bindValue(':did', $did);
            if ($stmt->execute()){
                $result = $stmt->fetchObject("Depense\Depense");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function updateDepense($depense, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET date =:date, montant =:montant, libelle =:libelle, tag =:tag WHERE did =:did");
            $stmt->bindValue(':date', $depense->date);
            $stmt->bindValue(':montant', $depense->montant);
            $stmt->bindValue(':libelle', $depense->libelle);
            $stmt->bindValue(':tag', $depense->tag);
            $stmt->bindValue(':did', $depense->did);
            if ($stmt->execute()) {
                $noError = true;
                $message = "La dépense a bien été modifié";
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function deleteDepense($did, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE did = :did");
            $stmt->bindValue(':did', $did);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $message = "La dépense a bien été supprimée";
                $noError = true;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function getAllDepenseByGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid = :gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Depense\Depense");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function getThreeLastDepenseByGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid = :gid LIMIT 3");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Depense\Depense");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function getTotalByGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT SUM(montant) FROM ".self::TABLE_NAME." WHERE gid = :gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_NUM);
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result[0];
    }

    public function getAvgByGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT AVG(montant) FROM ".self::TABLE_NAME." WHERE gid = :gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_NUM);
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result[0];
    }

    public function getTotalFromUserByGid($uid, $gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT SUM(montant) FROM ".self::TABLE_NAME." WHERE gid = :gid && uid = :uid");
            $stmt->bindValue(':gid', $gid);
            $stmt->bindValue(':uid', $uid);
            if ($stmt->execute()){
                $result = $stmt->fetch(PDO::FETCH_NUM);
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result[0];
    }
}