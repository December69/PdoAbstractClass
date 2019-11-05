<?php
/*
Classe de gestion de base de notre base de donées
*/

abstract class pdoRepository{
    const USERNAME="Identifiant";
    const PASSWORD="MotDePasse";
    const HOST="localhost";
    const DB="NomDataBase";

    private function getConnection(){
        $username = self::USERNAME;
        $password = self::PASSWORD;
        $host = self::HOST;
        $db = self::DB;
        $connection = new PDO("mysql:dbname=$db;host=$host", $username, $password,
            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8"));
        //le format utf8 est très important, notamment pour JSON !
        return $connection;
    }

    /*************************************
     * Méthode d'excution d'un SELECT
     * $sql : requête sql (chaine de caractere)
     *$arg : tableau à deux dimensions des arguments de la requête sous la forme $arg[i] = [valeur, PDO::PDO::PARAM]
     */
    protected function queryList($sql, $args = null){
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        if($args != null) {
            $size = count($args);
            for($i = 0; $i<$size ; $i++){
                $stmt->bindValue($i+1, $args[$i][0], $args[$i][1]);
            }
        }
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /*************************************
     * Méthode d'excution d'une NON-QUERY (UPDATE, DELETE)
     *$sql : requête sql (chaine de caractere)
     *$arg : tableau à deux dimensions des arguments de la requête sous la forme $arg[i] = [valeur, PDO::PDO::PARAM]
     */
    protected function execute($sql, $args=null){
        $connection = $this->getConnection();
        $stmt = $connection->prepare($sql);
        if($args != null) {
            $size = count($args);
            for($i = 0; $i<$size ; $i++){
                $stmt->bindValue($i+1, $args[$i][0], $args[$i][1]);
            }
        }
        $stmt->execute();
        $count = $stmt->rowCount();
        return $count != 0 ? $count : $stmt->errorInfo();
    }
}

?>
