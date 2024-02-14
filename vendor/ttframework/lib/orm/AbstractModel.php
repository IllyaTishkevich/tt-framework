<?php

namespace tt\lib\orm;

class AbstractModel extends \tt\AbstractObject
{
    protected $identifier = '';

    protected $mainTable = '';

    protected $tableFieldsInfo = [];

    protected $connection;

    public function __construct()
    {
        $this->connection = \tt\lib\PDO::getConnect();
    }

    public function load()
    {
        $sql = "SELECT * FROM {$this->mainTable} WHERE {$this->identifier} = {$this->getData($this->identifier)}";
        $row = $this->connection->queryFetch($sql);
        foreach ($row[0] as $key => $value)
        {
            $this->setData($key, $value);
        }
    }

    public function loadById(int $id)
    {
        $sql = "SELECT * FROM {$this->mainTable} WHERE {$this->identifier} = {$id}";
        $row = $this->connection->queryFetch($sql);
        foreach ($row[0] as $key => $value)
        {
            $this->setData($key, $value);
        }
    }

    public function getId()
    {
        return $this->getData($this->identifier);
    }

    public function save()
    {
        $id = $this->getId();

        $tableInfo = $this->getTableFieldsData();
        if(isset($id)) {
            $data = $this->getData();
            $string = '';
            foreach ($data as $field => $value)
            {
                if(($field !== $this->identifier) && (isset($tableInfo[$field])) ) {
                    $string .= '`'.$field.'` = \''.$value.'\' , ';
                }
            }
            $string = substr($string, 0, -3);
            $sql = "UPDATE `{$this->mainTable}` SET {$string} WHERE `{$this->identifier}` = '{$this->getId()}';";
            $this->connection->query($sql);
        } else {
            $data = $this->getData();
            $fieldsString = '';
            $valuesString = '';
            foreach ($data as $field => $value)
            {
                if(($field !== $this->identifier) && (isset($tableInfo[$field])) ) {
                    $fieldsString .= '`'.$field.'` , ';
                    $valuesString .= '\''.$value.'\' , ';
                }
            }

            $fieldsString = substr($fieldsString, 0, -3);
            $valuesString = substr($valuesString, 0, -3);

            $sql = "INSERT INTO `{$this->mainTable}` ({$fieldsString}) VALUES ({$valuesString});";
            $this->connection->query($sql);

            $sql = "SELECT @@identity as id;";
            $result = $this->connection->queryFetch($sql);

            $this->setData($this->identifier, $result['0']['id']);
        }
    }

    protected function getTableFieldsData()
    {
        if(empty($this->tableFieldsInfo)) {
            $sql = "SHOW COLUMNS FROM {$this->mainTable}";
            $tableFieldsInfo = $this->connection->queryFetch($sql);
            foreach ($tableFieldsInfo as $info)
            {
                $data[$info['Field']] = $info;
            }

            $this->tableFieldsInfo = $data;
        }

        return $this->tableFieldsInfo;
    }
}