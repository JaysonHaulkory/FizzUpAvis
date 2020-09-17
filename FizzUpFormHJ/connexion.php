<?php
    try
    {   
        $dbname = 'fizzform';
        $dbhost = 'localhost';
        $dbusername = 'root';
        $dbuserpsw = '';
        $bdd = new PDO('mysql:host=' . $dbhost . ';dbname=' . $dbname . ';charset=utf8', $dbusername , $dbuserpsw);
    }
    catch (Exception $e)
    {
        die('Erreur : ' . $e->getMessage());
    }
?>