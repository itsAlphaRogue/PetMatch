<?php

namespace PetMatch;

use mysqli;

class Database {
    private static ?mysqli $connection = null;

    public static function getConnection(): mysqli {
        if (self::$connection === null) {
            global $con;
            if (isset($con) && $con instanceof mysqli) {
                self::$connection = $con;
            } else {
                $hostname = "localhost";
                $username = "root";
                $password = "itsme";
                $db = "petmatch";
                self::$connection = mysqli_connect($hostname, $username, $password, $db);
            }
        }
        return self::$connection;
    }
}
