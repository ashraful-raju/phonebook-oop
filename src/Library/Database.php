<?php

namespace Phonebook\Library;

use mysqli;

abstract class Database
{
    protected $item;
    protected $connection;
    protected $table;

    function __construct()
    {
        $this->connection = new mysqli(
            'localhost',
            'root',
            '',
            'phonebook',
            3306
        );
    }

    public function getAll()
    {
        $data = [];
        $result = $this->connection->query("select * from $this->table");
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    public function where(array $conditions, $singleResult = false)
    {
        $data = [];
        $conditionStr = $this->getWhereClause($conditions, "AND");

        $sql = "SELECT * FROM $this->table WHERE $conditionStr";

        $result = $this->connection->query($sql);

        if ($singleResult) {
            return $result->fetch_assoc();
        }

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $data[] = $row;
            }
        }

        return $data;
    }

    protected function getWhereClause($conditions, $operator = "AND")
    {
        $str = "";
        foreach ($conditions as $key => $value) {
            $str .= "$key=\"$value\" " . $operator;
        }
        return trim($str, " AND");
    }

    function insert(array $data)
    {
        $column = trim(join(", ", array_keys($data)), ', ');
        $values = join('", "', array_values($data));

        $sql = "INSERT INTO $this->table ($column) VALUES (\"$values\")";

        return $this->connection->query($sql) === TRUE;
    }

    function update(array $data, $id)
    {
        $items = array_map(function ($item, $key) {
            return "$key=\"$item\"";
        }, $data, array_keys($data));

        $values = join(",", $items);

        $sql = "UPDATE $this->table SET $values WHERE id=$id";

        return $this->connection->query($sql) === TRUE;
    }

    function getConnection()
    {
        return $this->connection;
    }

    function __get($name)
    {
        return $this->item[$name] ?? null;
    }

    function __destruct()
    {
        $this->connection->close();
    }
}
