<?php
namespace Versement;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Versement {
    public $vid;
    public $uidVerseur;
    public $uidBenefi;
    public $gid;
    public $montant;
    public $estConfirme;
}

class VersementRepository {
    const TABLE_NAME = 'Versement';

    public function storeVersements($versements, &$message) {
        $noError = false;
        $bdd     = null;

        foreach ($versements as $v) {
            try {
                $bdd = DBLink::connect2db(MYDB, $message);
                $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME."
                    (uidVerseur, uidBenefi, gid, montant, estConfirme) VALUES (:uidVerseur, :uidBenefi, :gid, :montant, :estConfirme)");
                $stmt->bindValue(':uidVerseur', $v->uidVerseur);
                $stmt->bindValue(':uidBenefi', $v->uidBenefi);
                $stmt->bindValue(':gid', $v->gid);
                $stmt->bindValue(':montant', $v->montant);
                $stmt->bindValue(':estConfirme', $v->estConfirme);
                if($stmt->execute()) {
                    $message = "Solde confirmé";
                    $noError = true;
                }
                $stmt = null;
            } catch(PDOException $e) {
                $message .= $e->getMessage(). '<br>';
            }
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function getVersementsofGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE gid = :gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Versement\Versement");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }

    public function deleteVersementsOfGid($gid, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("DELETE FROM " . self::TABLE_NAME . " WHERE gid=:gid");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute() && $stmt->rowCount() > 0) {
                $message = "Le solde du groupe a bien été annulé";
                $noError = true;
            }
        } catch (Exception $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function confirmerVersement($vid) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET estConfirme = 1  WHERE vid =:vid ");
            $stmt->bindValue(':vid', $vid);
            if ($stmt->execute()) {
                $noError = true;
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }
}