<?php

class FxDataModel {

    const FX_RATES_KEY = "fx.rates.file";
    const DST_AMT_KEY = "dst.amt";
    const DST_CUCY_KEY = "dst.cucy";
    const SRC_AMT_KEY = "src.amt";
    const SRC_CUCY_KEY = "src.cucy";
    const SESSION_CLASS_KEY = "FxDataModel";
    const DSN_KEY = "dsn";
    const DB_USERNAME_KEY = "DBuser";
    const DB_PASSWORD_KEY = "DBpass";
    const PREPARED_STATEMENT_KEY = "preparedStatement";

    private $iniArray;
    private $FxCurrencies = array();
    private $FxRate = array();

    function __construct() {
        define("FX_CALC_INI_FILE", "fxCalc.ini");
        $this->iniArray = parse_ini_file(FX_CALC_INI_FILE);

        $dsn = "mysql:host=localhost;dbname=" . ($this->iniArray[self::DSN_KEY]);
        $db = new PDO($dsn, $this->iniArray[self::DB_USERNAME_KEY], $this->iniArray[self::DB_PASSWORD_KEY]);

        $query = $this->iniArray[self::PREPARED_STATEMENT_KEY];
        $statement = $db->prepare($query);
        $statement->execute();
        $results = $statement->fetchAll();

        //$query = "SELECT fxRate FROM fxrates";
        //$statement = $db->prepare($query);
        //$statement->execute();
        //$fxRates = $statement->fetchAll();

        $statement->closeCursor();


        $len = sizeof($results);
        $lastResult = null;
        for ($i = 0; $i < $len; $i++) {
            if ($results[$i][0] != $lastResult)
                array_push($this->FxCurrencies, $results[$i][0]);
            $lastResult = $results[$i][0];
        }

        $len = sizeof($this->FxCurrencies);
        $len2 = $len;
        $x = 0;
        for ($i = 0; $i < $len; $i++) {
            $arr = array();
            for ($x; $x < $len2; $x++) {
                array_push($arr, $results[$x][1]);
            }
            $len2 += $len;
            array_push($this->FxRate, $arr);
        }
        $this->db = null;
    }

    public function getFxCurrencies() {
        return $this->FxCurrencies;
    }

    public function getFxRate($fromFX, $toFX) {
        return $this->FxRate[array_search($fromFX, $this->FxCurrencies)][array_search($toFX, $this->FxCurrencies)];
    }

    public function getIniArray() {
        return $this->iniArray;
    }

}
