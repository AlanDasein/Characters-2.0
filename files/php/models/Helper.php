<?php

class Helper {

    public static function encrypt($value) {
        $key = pack('H*', "bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3");
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $utf8 = utf8_encode($value);
        $cipher = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $utf8, MCRYPT_MODE_CBC, $iv);
        $cipher = $iv.$cipher;
        $base64 = base64_encode($value);
        return $base64;
    }

    public static function decrypt($value) {return base64_decode($value);}

    public static function xssClean($data) {
        $data = trim($data);
        $data = str_replace(array('&amp;','&lt;','&gt;'), array('&amp;amp;','&amp;lt;','&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:*[^>]*+>#iu', '$1>', $data);
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        }
        while ($old_data !== $data);
        return $data;
    }

    public static function validMail($mail) {
        $valid = 0;
        if(
            (strlen($mail) >= 6 && substr_count($mail, "@") ==1 ) && (substr($mail, 0, 1) != "@") && (substr($mail, strlen($mail) - 1, 1) != "@") &&
            !strstr($mail, "'") && !strstr($mail, '"') && !strstr($mail, "\\") && !strstr($mail, "$") && !strstr($mail, " ") && substr_count($mail, ".") >= 1
        ) {
            $domain = substr(strrchr($mail, '.'), 1);
            if(strlen($domain) > 1 && strlen($domain) < 5 && (!strstr($domain, "@"))) {
                $bf_domain = substr($mail, 0, strlen($mail) - strlen($domain) - 1);
                $last_char = substr($bf_domain, strlen($bf_domain) - 1, 1);
                if($last_char != "@" && $last_char != ".") $valid = 1;
            }
        }
        return $valid;
    }

    public static function getIp() {
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];
        if(filter_var($client, FILTER_VALIDATE_IP)) {
            $ip = $client;
        }
        else if(filter_var($forward, FILTER_VALIDATE_IP)) {$ip = $forward;}
        else {$ip = $remote;}
        return $ip;
    }

    public static function getPath($file, $path) {
        $called = str_replace("\\", "/", pathinfo($file)["dirname"]);
        $root = str_replace($_SERVER["DOCUMENT_ROOT"], "", $called);
        $from = '/'.preg_quote($root, '/').'/';
        return preg_replace($from, "", $path, 1);
    }

    public static function getEnv($settings, $path) {
        $pieces = explode("/", $path);
        $lang = empty($pieces[1]) ? $settings["langs"][0] : (in_array($pieces[1], $settings["langs"]) ? $pieces[1] : $settings["langs"][0]);
        $page = empty($pieces[2]) ? $settings["pages"][0] : (in_array($pieces[2], $settings["pages"]) ? $pieces[2] : $settings["pages"][0]);
        $action = empty($pieces[3]) ? "" : (in_array($pieces[3], $settings["controllers"]) ? $pieces[3] : "");
        return array("lang" => $lang, "page" => $page, "action" => $action);
    }

    public static function getKaptcha() {
        $letters = array("A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z");
        $numbers = array("min" => 0, "max" => 99);
        $group = rand(0, 1);
        $val = $group === 0 ? rand(0, count($letters) - 3) : rand($numbers["min"], $numbers["max"]);
        $total = $val + 3;
        $values = array();
        for($i = $val;$i < $total;$i++) $values[] = $group === 0 ? $letters[$i] : $i;
        $values[1] = Helper::encrypt($values[1]);
        return $values;
    }

    public static function getData($con, $param, $values = array()) {
        $sql = "SELECT ".implode(",", $param["fields"])." FROM ".$param["table"].
            (empty($param["join"]) ? "" : (" ".implode(" ", $param["join"])." ")).
            (empty($param["where"]) ? "" : (" WHERE ".implode(" ", $param["where"])." ")).
            (empty($param["groupby"]) ? "" : (" GROUP BY ".implode(" ", $param["groupby"])." ")).
            (empty($param["having"]) ? "" : (" HAVING ".implode(" ", $param["having"])." ")).
            (empty($param["orderby"]) ? "" : (" ORDER BY ".implode(" ", $param["orderby"])." ")).
            (empty($param["limit"]) ? "" : (" LIMIT ".$param["limit"]));
        return $q = $con->query("SELECT", $sql, $values);
    }

    public static function setData($con, $table, $param) {
        $sql = array("fields" => array(), "values" => array(), "mask" => array());
        foreach($param as $k => $v) {
            $sql["fields"][] = "`$k`";
            if(is_numeric($v) || strstr($v, "()")) $sql["mask"][] = $v;
            else {
                $sql["mask"][] = "?";
                $sql["values"][] = Helper::xssClean($v);
            }
        }
        $sql["string"] = "INSERT INTO `$table` (".implode(",", $sql["fields"]).") VALUES (".implode(",", $sql["mask"]).")";
        return $con->query("INSERT", $sql["string"], $sql["values"]);
    }

    public static function manageCounters($file) {
        $counter = file_get_contents($file);
        $counter++;
        $handle = fopen($file, "w");
        fwrite($handle, $counter);
        fclose($handle);
    }

    public static function drawSection($node, $param, $which = "MENU") {
        $r = "";
        if($which === "MENU") {
            foreach($node as $v) {
                $r .= "<li".($param["page"] == $v["link"] ? " class='active'" : "")."><a href='".$param["lang"]."/".$v["link"]."'>".$v."</a></li>";
            }
        }
        else if($which === "SELECTOR") {
            foreach($node as $v) {
                $r .= "<li><a href='".$v["link"]."/".$param["page"]."'><img src='files/assets/flag_".$param["lang"].".png' />".$v."</a></li>";
            }
        }
        else if($which === "BUTTONS") {
            foreach($node as $v) {
                $r .= "<div class='col-xs-12 col-md-4'><button type='button' class='btn btn-default' role='command' dt-action='".$v["action"]."' dt-data='{\"dummy\":0}'>".$v."</button></div>";
            }
        }
        return $r;
    }

    public static function applyFormat($val, $sex, $lang) {
        switch($lang) {
            case "sp":
                $output = str_replace("#", ($sex === 1 ? "" : "a"), str_replace("@", ($sex === 1 ? "o" : "a"), str_replace(" #", ($sex === 1 ? "el" : "la"), $val)));
                break;
        }
        return $output;
    }

    public static function formatNumber($val, $double = false) {
        $res = $val;
        $res .= ($val == (int)$val ? ".0" : "");
        if($double) $res .= (($val * 10) == ((int)$val * 10) ? "0" : "");
        return $res;
    }

    public static function NumberShort($val) {
        if($val == 0) return $val;
        $abbr = array(12 => 'T', 9 => 'B', 6 => 'M', 3 => 'K', 0 => '');
        foreach($abbr as $a => $b) {
            if($val >= pow(10, $a)) return round(floatval($val / pow(10, $a)), 1).$b;
        }
    }

}