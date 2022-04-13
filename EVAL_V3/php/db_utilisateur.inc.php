<?php
namespace Utilisateur;
require_once 'db_link.inc.php';

use DB\DBLink;
use PDO;
use PDOException;

setlocale(LC_TIME, 'fr_FR.utf8', 'fra');

class Utilisateur {
    public $uid;
    public $courriel;
    public $nom;
    public $prenom;
    public $motPasse;
    public $estActif;

    public function __set($prop, $val) {
        switch ($prop) {
            case "courriel": $this->$prop = strtolower($val); break;
            case "nom": $this->$prop = strtoupper($val); break;
            default: $this->$prop = $val;
        }
    }
}

class UtilisateurRepository {
    const TABLE_NAME = 'Utilisateur';

    public function existInDB($courriel, &$message) {
        $result = false;
        $bdd    = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." WHERE courriel = :courriel");
            $stmt->bindValue(':courriel', $courriel);
            if($stmt->execute()) {
                if($stmt->fetch() !== false) {
                    $result = true;
                }
            } else {
                $message .= 'Une erreur système est survenue.<br> Veuillez essayer à nouveau plus tard ou contactez l\'administrateur du site. (Code erreur E: ' . $stmt->errorCode() . ')<br>';
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage().'<br>';
        }
        DBLink::disconnect($bdd);
        return $result;
    }

    public function storeMember($member, &$message) {
        $noError = false;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("INSERT INTO ".self::TABLE_NAME." (courriel, nom, prenom, motPasse) VALUES (:courriel, :nom, :prenom, :motPasse)");
            $stmt->bindValue(':courriel', $member->courriel);
            $stmt->bindValue(':nom', $member->nom);
            $stmt->bindValue(':prenom', $member->prenom);
            $stmt->bindValue(':motPasse', $member->motPasse);
            if ($stmt->execute()){
                $message .= 'Votre compte a bien été créé' ;
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

    public function updateUtilisateur($utilisateur, $uid, &$message) {
        $noError = false;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("UPDATE " . self::TABLE_NAME . " SET courriel=:courriel, nom=:nom, prenom=:prenom  WHERE uid =:uid");
            $stmt->bindValue(':courriel', $utilisateur->courriel);
            $stmt->bindValue(':nom', $utilisateur->nom);
            $stmt->bindValue(':prenom', $utilisateur->prenom);
            $stmt->bindValue(':uid', $uid);
            if ($stmt->execute()) {
                $noError = true;
                $message = "Votre profil a bien été modifié";
            }
        } catch (PDOException $e) {
            $message .= $e->getMessage() . '<br>';
        }
        DBLink::disconnect($bdd);
        return $noError;
    }

    public function getUtilisateurByData($courriel, &$message) {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE courriel = :courriel");
            $stmt->bindValue(':courriel', $courriel);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("Utilisateur\Utilisateur");
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

    public function getUtilisateurById($uid) {
        $result = null;
        $bdd = null;
        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM " . self::TABLE_NAME . " WHERE uid = :uid");
            $stmt->bindValue(':uid', $uid);
            if ($stmt->execute()) {
                $result = $stmt->fetchObject("Utilisateur\Utilisateur");
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

    public function getUtilisateursFromGid($gid) {
        $result  = null;
        $bdd     = null;

        try {
            $bdd = DBLink::connect2db(MYDB, $message);
            $stmt = $bdd->prepare("SELECT * FROM ".self::TABLE_NAME." u JOIN Participer p on u.uid = p.uid WHERE p.gid = :gid && p.estConfirme = 1");
            $stmt->bindValue(':gid', $gid);
            if ($stmt->execute()){
                $result = $stmt->fetchAll(PDO::FETCH_CLASS, "Utilisateur\Utilisateur");
            }
            $stmt = null;
        } catch(PDOException $e) {}

        DBLink::disconnect($bdd);
        return $result;
    }
}