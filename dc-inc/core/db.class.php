<?php

/**
 * Datenbank klasse die über PDO verwalten wird mit autoinsert und update funktionen
 *
 * @author Sascha Wäschle <sw@d4ho.de>
 * @since 0.4
 *
 * @private PDO $sql Database connection.
 */
class Db
{

    private static $sql;

    public static function init($sql)
    {
        if (!empty($sql)) {
            self::connect($sql['host'], $sql['db'], $sql['user'], $sql['pw']);
        }
    }

    public static function get_handler() {
        return self::$sql;
    }

    /**
     * Datenbank wird über PDO verbunden.
     *
     * @since 0.4
     * @param  String $host Hostname from Database.
     * @param  String $db Database name.
     * @param  String $user Username from Database.
     * @param  String $pw Database password.
     * @return PDO Database connection.
     */
    public static function connect($host, $db, $user, $pw)
    {
        $db = "mysql:host=$host;dbname=$db;charset=utf8 - User:$user - Password: - $pw";
        $handler = new PDO ("mysql:host=$host;dbname=$db;charset=utf8", $user, $pw);
        if (!$handler) {
            Debug::log("SQL Error: $db");
        }
        self::$sql = $handler;
        return $handler;
    }

    /**
     * Query funktion um mit Parameter einen Query auszuführen
     *
     * @since 0.4
     * @param  String $qry SQL String.
     * @param  array() $params Parameter die im SQL String ersetzt werden.
     * @param int|\PDO $fetchmode Fetchmode für den excute.
     * @return PDO Fetch Ergebnisse.
     */
    public static function query($qry, $params = array(), $fetchmode = PDO::FETCH_ASSOC)
    {
        $stmt = self::$sql->prepare($qry);
        $stmt->setFetchMode($fetchmode);
        $ret = false;
        if (!is_array($params)) $params = array();
        if ($stmt->execute($params)) {
            $ret = strpos($qry, 'LIMIT 1') ? $stmt->fetch() : $stmt->fetchAll();
        }
        Debug::log($stmt, 'PDO', $qry);
        return $ret;
    }

    /**
     * Query funktion um einen Query auszuführen
     *
     * @since 0.4
     * @param  String $qry SQL String.
     * @param int|\PDO $fetchmode Fetchmode für den excute.
     * @return PDO Fetch Ergebnisse.
     */
    public static function npquery($qry, $fetchmode = PDO::FETCH_ASSOC)
    {
        if (self::$sql) {
            $stmt = self::$sql->prepare($qry);
            $stmt->setFetchMode($fetchmode);
            $ret = false;
            if ($stmt->execute()) {
                $ret = strpos($qry, 'LIMIT 1') ? $stmt->fetch() : $stmt->fetchAll();
            }
            Debug::log($stmt, 'PDO', $qry);
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * Query funktion um mit Parameter einen Query auszuführen ohne Rückgabe.
     *
     * @since 0.4
     * @param  String $qry SQL String.
     * @param  array() $params Parameter die im SQL String ersetzt werden.
     * @param int|\PDO $fetchmode Fetchmode für den excute.
     * @return True or False für Update oder Insert.
     */
    public static function nrquery($qry, $params = array(), $fetchmode = PDO::FETCH_ASSOC)
    {
        if (self::$sql) {
            $stmt = self::$sql->prepare($qry);
            $stmt->setFetchMode($fetchmode);
            if (!is_array($params)) $params = array();
            $ret = false;
            if ($stmt->execute($params)) {
                $ret = true;
            }
            Debug::log($stmt, 'PDO', $qry);
            return $ret;
        } else {
            return false;
        }
    }

    /**
     * Database disconnect
     *
     * @since 0.4
     */
    public static function closeConnection()
    {
        self::$sql = null;
    }

    /**
     * Query funktion um einen Update automatisiert durchzuführen
     *
     * @since 0.4
     * @param  String $table Tabellenname der Datenbank die geupdated werden soll.
     * @param  int $id ID der Zeile die geupdated werden soll.
     * @param  array() $new Array mit inhalt der Spalten.
     * @return PDO Fetch Ergebnisse.
     */
    public static function update($table, $id, $new)
    {
        if (self::$sql) {
            $sql = "UPDATE $table SET ";
            foreach ($new as $col => $value) {
                $value = self::esc($value);
                $up[] = "`$col` = $value";
            }
            $sql .= implode(',', $up) . " WHERE id = $id LIMIT 1";
            return self::nrquery($sql);
        } else {
            return false;
        }
    }

    /**
     * Query zum löschen einer Zeile
     *
     * @since 0.4
     * @param  String $table Tabellenname der Datenbank.
     * @param  int $id ID der Zeile die gelöscht werden soll.
     * @return True or False bei Fehler.
     */
    public static function delete($table, $id)
    {
        $sql = "DELETE FROM $table WHERE id = :id";
        return self::nrquery($sql, array('id' => $id));
    }

    /**
     * Query insert automatisch generiert.
     *
     * @since 0.4
     * @param  String $table Tabellenname der Datenbank die geupdated werden soll.
     * @param  array() $new Array mit inhalt der Spalten.
     * @return Fetch Ergebnisse.
     */
    public static function insert($table, $new = array())
    {
        $sql = "INSERT INTO $table (";
        foreach ($new as $col => $value) {
            $tab[] = "`$col`";
            $val[] = self::esc($value);;
        }
        $sql .= implode(',', $tab) . ") VALUES (" . implode(',', $val) . ")";
        return self::nrquery($sql);
    }

    public static function drop($table)
    {
        $sql = "DROP TABLE $table";
        self::npquery($sql);
    }

    /**
     * Gibt die letzt betroffene ID zurück
     *
     * @since 0.4
     * @return zuletzt eingefügte ID der Zeile.
     */
    public static function get_last_id()
    {
        return self::$sql->lastInsertId();
    }

    /**
     * Escapt MYSQL Injections und fügt Quote tags hinzu.
     *
     * @since 0.4
     * @param  String $val String der Variable die gefiltert werden soll.
     * @return gefilter String mit Tags.
     */
    public static function esc($val)
    {
        return self::$sql->quote($val);
    }
}