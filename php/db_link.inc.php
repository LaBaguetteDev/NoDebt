<?php
namespace DB;
require 'config.inc.php';
use PDO;
use PDOException;
class DBLink {
    public static function connect2db($base, &$message){
        try {
            $link = new PDO('mysql:host=' . MYHOST . ';dbname=' . $base . ';charset=UTF8', MYUSER, MYPASS);
            $link->exec("set names utf8");
            $link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            $message .= $e->getMessage().'<br>';
            $link = false;
        }
        return $link;
    }

    public static function disconnect (&$link) {
        $link = null;
    }
}
?>
