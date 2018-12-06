<?php

class base
{

    public static $errors = false;

    public static function genError($url, $values, $array)
    {

        self::$errors = true;

        echo "<br><b>Foi encontrado um erro!</b> <br> <b>Url: </b>$url <br>";
        if ($values > 0) {
            echo "Valores a serem enviados:<br> <pre>";
            print_r($values);
            echo "</pre><br> ";
        }
        echo "<b>Resposta recebida</b>: <br> <pre>";
        print_r($array);
        echo "</pre>";
    }

    public static function triggerFatalError($message)
    {
        trigger_error($message, E_USER_ERROR);
    }

    public static function triggerError($message)
    {
        echo $message;
        return(TRUE);
    }

    public static function selectCompanies()
    {
        $results = cURL::simple("companies/getAll");
        return($results);
    }

}

class moloniDB
{
    public static function getInfo()
    {
        global $wpdb;
        $results = $wpdb->get_row("SELECT * FROM moloni_api", ARRAY_A);
        return($results);
    }

    public static function setTokens($access_token, $refresh_token)
    {
        global $wpdb;
        $wpdb->query("TRUNCATE moloni_api");
        $wpdb->query("INSERT INTO moloni_api(main_token, refresh_token) VALUES('$access_token', '$refresh_token')");
        $results = $wpdb->get_row("SELECT * FROM moloni_api", ARRAY_A);
        return($results);
    }

    public static function refreshTokens()
    {
        global $wpdb;
        $check = $wpdb->get_row("SELECT expiretime FROM moloni_api", ARRAY_A);
        if (!isset($check['expiretime'])) {
            $wpdb->query("ALTER TABLE moloni_api ADD expiretime varchar(250)");
        } else {
            $expire = $check['expiretime'];
        }

        $dbInfo = self::getInfo();
        if ($expire < time()) {
            $results = cURL::refresh($dbInfo['refresh_token']);
            $timeNow = time();
            $timeExpire = $timeNow + 3000;
            $wpdb->query("UPDATE moloni_api SET main_token = '" . $results['access_token'] . "', refresh_token = '" . $results['refresh_token'] . "', expiretime = '" . $timeExpire . "'");
        }

        $results = $wpdb->get_row("SELECT * FROM moloni_api", ARRAY_A);

        return($results);
    }

    public static function defineValues()
    {
        $results = self::getInfo();
        define("SESSION_ID", $results['id']);
        define("ACCESS_TOKEN", trim($results['main_token']));
        define("REFRESH_TOKEN", $results['refresh_token']);
        if ($results['company_id'] <> '') {
            define("COMPANY_ID", $results['company_id']);
        }
    }

    public static function defineConfigs()
    {
        global $wpdb;
        $results = $wpdb->get_results("SELECT * FROM moloni_api_config", ARRAY_A);
        foreach ($results as $result) {
            define(strtoupper($result['config']), $result['selected']);
        }
    }

}

class moloniBasics
{
    public static function documentSets()
    {
        $values = array("company_id" => COMPANY_ID);
        $results = cURL::simple("documentSets/getAll", $values);
        return($results);
    }

    public static function paymentMethods()
    {
        $values = array("company_id" => COMPANY_ID);
        $results = cURL::simple("paymentMethods/getAll", $values);
        return($results);
    }

    public static function measurementUnits()
    {
        $values = array("company_id" => COMPANY_ID);
        $results = cURL::simple("measurementUnits/getAll", $values);
        return($results);
    }

    public static function exemptionReasons()
    {
        $results = cURL::simple("taxExemptions/getAll");
        return($results);
    }

    public static function maturityDates()
    {
        $values['company_id'] = COMPANY_ID;
        $results = cURL::simple("maturityDates/getAll", $values);
        return($results);
    }

    public static function countries()
    {
        $results = cURL::simple("countries/getAll");
        return($results);
    }

    public static function languages()
    {
        $results = cURL::simple("languages/getAll");
        return($results);
    }

}

class general
{
    public static function verifyZip($zip)
    {
        $regexp = "\d{4}-\d{3}";
        if (preg_match($regexp, $zip)) {
            $zip = $zip;
        } else {
            $zip = "1000-100";
        }
        return($zip);
    }

    public static function getCountryID($id)
    {
        $resultsMoloni = moloniBasics::countries();
        foreach ($resultsMoloni as $result) {
            if (strtoupper($result["iso_3166_1"]) == $id) {
                return($result['country_id']);
            }
        }
        return("1");
    }

    public static function getLanguageID($id)
    {
        $resultsMoloni = moloniBasics::languages();
        foreach ($resultsMoloni as $result) {
            if (strtoupper($result["code"]) == $id) {
                return($result['language_id']);
            }
        }
        return("1");
    }

}

?>