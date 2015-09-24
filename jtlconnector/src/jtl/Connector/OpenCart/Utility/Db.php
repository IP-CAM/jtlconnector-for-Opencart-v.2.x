<?php
namespace jtl\Connector\OpenCart\Utility;

use jtl\Connector\Core\Database\Mysql;
use jtl\Connector\Core\Utilities\Singleton;

class Db extends Singleton
{
    private $db;

    public function __construct()
    {
        $mysql = Mysql::getInstance();
        $mysql->connect([
            'host' => DB_HOSTNAME,
            'name' => DB_DATABASE,
            'user' => DB_USERNAME,
            'password' => DB_PASSWORD
        ]);
        $mysql->DB()->set_charset("utf8");
        $this->db = $mysql;
    }

    public function query($query, ...$params)
    {
        return $this->db->query(sprintf($query, $params));
    }

    public function queryOne($query, ...$params)
    {
        $return = null;
        $result = mysqli_query($this->db->DB(), sprintf($query, $params));
        if ($result !== false) {
            $return = mysqli_fetch_row($result)[0];
        }
        return $return;
    }

    public function update($obj, $table, $key, $value, array $ignores = null)
    {
        if (is_object($obj) && strlen($table) > 0) {
            if ((is_array($key) && is_array($value)) || (strlen($key) > 0 && strlen($value) > 0)) {
                $query = "UPDATE " . $table . " SET ";

                $sets = array();

                $members = array_keys(get_object_vars($obj));
                if (is_array($members) && count($members) > 0) {
                    foreach ($members as $member) {
                        if ($ignores !== null && is_array($ignores) && count($ignores) > 0) {
                            if (in_array($member, $ignores)) {
                                continue;
                            }
                        }

                        if (!is_array($obj->$member) && !is_object($obj->$member)) {
                            $membervalue = $this->db->quote($obj->$member);
                            if ($obj->$member === null) {
                                $membervalue = "NULL";
                            }

                            $sets[] = "{$member} = {$membervalue}";
                        }
                    }

                    $query .= implode(', ', $sets);

                    if (is_array($key) && is_array($value)) {
                        if (count($key) == count($value)) {
                            foreach ($key as $i => $keyStr) {
                                if ($i > 0) {
                                    $query .= " AND {$keyStr} = " . $this->db->quote($value[$i]);
                                } else {
                                    $query .= " WHERE {$keyStr} = " . $this->db->quote($value[$i]);
                                }
                            }
                        }
                    } elseif (strlen($key) > 0 && strlen($value) > 0) {
                        $query .= " WHERE {$key} = " . $this->db->quote($value);
                    }

                    return $this->db->query($query);
                }
            }
        }

        return false;
    }
}