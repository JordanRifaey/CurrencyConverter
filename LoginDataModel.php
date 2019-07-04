<?php

class LoginDataModel {

    const HTML_USERNAME_KEY = "HTMLnameUsername";
    const HTML_PASSWORD_KEY = "HTMLnamePassword";
    const formHTMLNameAttribute = "form";
    const DSN_KEY = "dsn";
    const DB_USERNAME_KEY = "DBuser";
    const DB_PASSWORD_KEY = "DBpass";
    const PREPARED_STATEMENT_KEY = "preparedStatement";

    private $iniLoginAttributesArray;
    private $db;
    private $statement;

    function __construct() {
        define("iniLoginFile", "login.ini");
        $this->iniLoginAttributesArray = parse_ini_file(iniLoginFile);

        $dsn = "mysql:host=localhost;dbname=" . ($this->iniLoginAttributesArray[self::DSN_KEY]);
        $this->db = new PDO($dsn, $this->iniLoginAttributesArray[self::DB_USERNAME_KEY], $this->iniLoginAttributesArray[self::DB_PASSWORD_KEY]);
        $query = $this->iniLoginAttributesArray[self::PREPARED_STATEMENT_KEY];
        $this->statement = $this->db->prepare($query);
    }

    function __destruct() {
        $this->db = null;
    }

    public function validateUser($username, $password) {


        $this->statement->bindValue(":username", $username);
        $this->statement->execute();
        $resultSet = $this->statement->fetch();
        $this->statement->closeCursor();
        if ($password === $resultSet['password']) {
            return true;
        } else {
            return false;
        }
    }

    public function getIniLoginAttributes() {
        return $this->iniLoginAttributesArray;
    }

}
