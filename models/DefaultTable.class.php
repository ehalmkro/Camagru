<?php
require_once 'Core.class.php';

class DefaultTable
{
    private $pdo;
    public $tableName;
    public $rowsPerPage;
    public $pageNb;
    public $lastPage;
    public $fieldList;
    public $dataArray;
    public $errors;

    public function __construct()
    {
        $core = Core::getInstance();
        $this->pdo = $core->pdo;
        $this->tableName = 'default';
        $this->rowsPerPage = 10;
        $this->fieldList = array('column1', 'column2', 'column3');
        $this->fieldList['column1'] = array('pkey' => 'y');
    }

    public function getData($where)
    {
        $this->data_array = [];
        $pageNb = $this->pageNb;
        $rowsPerPage = $this->rowsPerPage;
        $this->numRows = 0;
        $this->lastPage = 0;

        if (empty($where)) {
            $where_str = NULL;
        } else {
            $where_str = "WHERE $where";
        }

        $query = "SELECT COUNT(*) FROM $this->tableName $where_str";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute();
        $queryData = $stmt->fetch();
        $this->numRows = $queryData[0];


        // PAGINATION:
        if ($this->numRows <= 0) {
            $this->pageNb = 0;
            return;
        }
        if ($rowsPerPage > 0) {
            $this->lastPage = ceil($this->numRows / $rowsPerPage);
        } else
            $this->lastPage = 1;
        if ($pageNb == '' OR $pageNb <= '1') {
            $pageNb = 1;
        } else if ($pageNb > $this->lastPage) {
            $pageNb = $this->lastPage;
        }
        $this->pageNb = $pageNb;
        if ($rowsPerPage > 0) {
            $limit_str = 'LIMIT ' . ($pageNb - 1) * $rowsPerPage . "," . $rowsPerPage;
        } else
            $limit_str = NULL;

        $query = "SELECT * FROM $this->tableName $where_str $limit_str";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return NULL;
        }
        $this->dataArray = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $stmt->closeCursor();
        return $this->dataArray;
    }

    public function insertRecord($fieldArray)
    {
        $fieldList = $this->fieldList;
        foreach ($fieldArray as $field => $value) {
            if (!in_array($field, $fieldList))
                unset($fieldArray[$field]);
        }
        $query = "INSERT INTO $this->tableName SET ";
        foreach ($fieldArray as $item => $value)
            $query .= "$item='$value', ";
        $query = rtrim($query, ', ');

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return FALSE;
        }
        return TRUE;
    }


    public function updateRecord($fieldArray)
    {
        $fieldList = $this->fieldList;
        foreach ($fieldArray as $field => $fieldValue) {
            if (!in_array($field, $fieldList)) {
                unset ($fieldArray[$field]);
            }
        }
        $where = NULL;
        $update = NULL;
        foreach ($fieldArray as $item => $value) {
            if (isset($fieldList[$item]['pkey']))
                $where .= "$item='$value' AND ";
            else
                $update .= "$item='$value', ";
        }
        $where = rtrim($where, ' AND ');
        $update = rtrim($update, ', ');
        $query = "UPDATE $this->tableName SET $update WHERE $where";
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return FALSE;
        }
        return TRUE;

    }

    public function deleteRecord($fieldArray)
    {
        $fieldList = $this->fieldList;
        $where = NULL;
        foreach ($fieldArray as $item => $value) {
            if (isset($fieldList[$item]['pkey'])) {
                $where .= "$item='$value' AND ";
            }
        }
        $where = rtrim($where, ' AND ');
        $query = "DELETE FROM $this->tableName WHERE $where";

        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute();
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage();
            $this->errors[] = $e->getMessage();
            return FALSE;
        }
        return TRUE;

    }

}