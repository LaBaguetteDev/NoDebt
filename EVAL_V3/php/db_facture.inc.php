<?php
namespace Facture;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Facture {
    public $fid;
    public $scan;
    public $did;
}

class FactureRepository {
    const TABLE_NAME = 'Facture';

    public function storeFacture($facture, &$message) {
        $noError = false;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME." (scan, did) VALUES (:scan, :did)");
            $stmt->bindValue(':scan', $facture->scan);
            $stmt->bindValue(':did', $facture->did);
            if ($stmt->execute()){
                $message .= ' La facture a bien été enregistrée' ;
                $facture->fid = $bdd->lastInsertId();
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

    public function getFacturesByDid($did) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE did = :did");
            $stmt->bindValue(':did', $did);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Facture\Facture");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function getFactureByFid($fid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE fid = :fid");
            $stmt->bindValue(':fid', $fid);
            if ($stmt->execute()){
                $result = $stmt->fetchObject("Facture\Facture");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteFacture($fid, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE fid = :fid");
            $stmt->bindValue(':fid', $fid);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $message = "La facture a bien été supprimée";
                $noError = true;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }
}