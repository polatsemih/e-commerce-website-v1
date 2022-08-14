<?php
class Database extends PDO
{
    function __construct()
    {
        parent::__construct('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . 'utf8mb4', DB_USER_NAME, DB_USER_PASS);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }

    function GetWithColumns(string $table_name, string $columns)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name);
        if ($thequery->execute()) {
            return $thequery->fetch(PDO::FETCH_ASSOC);
        }
    }
    function GetAllWithColumns(string $table_name, string $columns)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name);
        if ($thequery->execute()) {
            return $thequery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function GetWithColumnsBySimpleCondition(string $table_name, string $columns, string $condition)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        if ($thequery->execute()) {
            return $thequery->fetch(PDO::FETCH_ASSOC);
        }
    }
    function GetAllWithColumnsBySimpleCondition(string $table_name, string $columns, string $condition)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        if ($thequery->execute()) {
            return $thequery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function GetWithColumnsByStringCondition(string $table_name, string $columns, string $condition, string $string_data)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        $thequery->bindParam(1, $string_data, PDO::PARAM_STR);
        if ($thequery->execute()) {
            return $thequery->fetch(PDO::FETCH_ASSOC);
        }
    }
    function GetWithAllColumnsByStringCondition(string $table_name, string $condition, string $data)
    {
        $thequery = $this->prepare('SELECT * FROM ' . $table_name . ' ' . $condition);
        $thequery->bindParam(1, $data, PDO::PARAM_STR);
        if ($thequery->execute()) {
            return $thequery->fetch(PDO::FETCH_ASSOC);
        }
    }
    function GetAllWithColumnsByStringCondition(string $table_name, string $columns, string $condition, string $data)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        $thequery->bindParam(1, $data, PDO::PARAM_STR);
        if ($thequery->execute()) {
            return $thequery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function GetWithColumnsByArrayCondition(string $table_name, string $columns, string $condition, array $array_data)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        if ($thequery->execute($array_data)) :
            return $thequery->fetch(PDO::FETCH_ASSOC);
        endif;
    }
    function GetAllWithColumnsByArrayCondition(string $table_name, string $columns, string $condition, array $array_data)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' ' . $condition);
        if ($thequery->execute($array_data)) {
            return $thequery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function Create(string $table_name, string $columns, string $question_marks, array $values)
    {
        $thequery = $this->prepare('INSERT INTO ' . $table_name . ' (' . $columns . ') VALUES (' . $question_marks . ')');
        if ($thequery->execute($values) && $thequery->rowCount() === 1) {
            return 'Created';
        }
    }
    function Update(string $table_name, string $columns, array $values)
    {
        $thequery = $this->prepare('UPDATE ' . $table_name . ' SET ' . $columns . ' WHERE id=?');
        if ($thequery->execute($values) && $thequery->rowCount() === 1) {
            return 'Updated';
        }
    }
    function Delete(string $table_name, string $id)
    {
        $thequery = $this->prepare('DELETE FROM ' . $table_name . ' WHERE id=?');
        $thequery->bindParam(1, $id, PDO::PARAM_STR);
        if ($thequery->execute() && $thequery->rowCount() === 1) {
            return 'Deleted';
        }
    }
    function Search(string $table_name, string $columns, string $search_condition, array $search_input)
    {
        $thequery = $this->prepare('SELECT ' . $columns . ' FROM ' . $table_name . ' WHERE ' . $search_condition);
        if ($thequery->execute($search_input)) {
            return $thequery->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    function CreateColumn(string $table_name, string $column_name, string $column_type, string $column_property)
    {
        $thequery = $this->prepare('ALTER TABLE ' . $table_name . ' ADD ' . $column_name . ' ' . $column_type . ' ' . $column_property);
        if ($thequery->execute()) {
            return 'Column Created';
        }
    }
    function DeleteColumn(string $table_name, string $column_name)
    {
        $thequery = $this->prepare('ALTER TABLE ' . $table_name . ' DROP ' . $column_name);
        if ($thequery->execute()) {
            return 'Column Deleted';
        }
    }
    function RenameColumn(string $table_name, string $old_column, string $new_column)
    {
        $thequery = $this->prepare('ALTER TABLE ' . $table_name . ' CHANGE ' . $old_column . ' ' . $new_column . ' mediumint(8)');
        if ($thequery->execute()) {
            return 'Column Updated';
        }
    }
    function EmptyColumData(string $table_name, string $column, string $condition, string $data)
    {
        $thequery = $this->prepare('UPDATE ' . $table_name . ' SET ' . $column . '= null ' . $condition);
        $thequery->bindParam(1, $data, PDO::PARAM_STR);
        if ($thequery->execute()) {
            return 'Updated';
        }
    }
}
