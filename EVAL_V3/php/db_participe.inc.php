<?php
namespace Participer;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Participer {
    public $uid;
    public $gid;
    public $estConfirme;
}

class ParticiperRepository {
    const TABLE_NAME = 'Participer';

    public function setParticipe($participe) {
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
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $noError;
    }
}