<?php
class Database extends PDO
{
    function __construct()
    {
        parent::__construct('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . 'utf8mb4', DB_USER_NAME, DB_USER_PASS);
        $this->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    function BackUpSql(string $prepare, string $values = null)
    {
        do {
            $back_up_sql_id = strtolower(strtr(sodium_bin2base64(random_bytes(187), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'a', '_' => '9')));
            $back_up_sql_query_1 = $this->prepare('SELECT id FROM ' . TABLE_SQL_BACKUP . ' WHERE id=?');
            if (!empty($back_up_sql_query_1) && $back_up_sql_query_1->bindParam(1, $back_up_sql_id, PDO::PARAM_STR) && $back_up_sql_query_1->execute()) {
                $result_back_up_sql_query_1 = $back_up_sql_query_1->fetch(PDO::FETCH_ASSOC);
                if (empty($result_back_up_sql_query_1)) {
                    if (!empty($values)) {
                        $back_up_sql_prepare = 'INSERT INTO ' . TABLE_SQL_BACKUP . ' (id,user_ip,sql_query,sql_query_values) VALUES (?,?,?,?)';
                        $back_up_sql_execute = array($back_up_sql_id, $_SERVER['REMOTE_ADDR'], $prepare, $values);
                    } else {
                        $back_up_sql_prepare = 'INSERT INTO ' . TABLE_SQL_BACKUP . ' (id,user_ip,sql_query) VALUES (?,?,?)';
                        $back_up_sql_execute = array($back_up_sql_id, $_SERVER['REMOTE_ADDR'], $prepare);
                    }
                    $back_up_sql_query_2 = $this->prepare($back_up_sql_prepare);
                    if (!empty($back_up_sql_query_2) && $back_up_sql_query_2->execute($back_up_sql_execute) && $back_up_sql_query_2->rowCount() == 1) {
                        return true;
                    } else {
                        return false;
                    }
                }
            }
        } while (true);
    }
    function Get(string $table, string $columns, string $condition, $values, string $type)
    {
        $prepare = 'SELECT ' . $columns . ' FROM ' . $table;
        if (!empty($condition)) {
            $prepare .= ' ' . $condition;
        }
        $sql_query = $this->prepare($prepare);
        $result_sql_query = false;
        if (!empty($sql_query)) {
            if (!empty($values)) {
                if (is_string($values) && $this->BackUpSql($prepare, $values) && $sql_query->bindParam(1, $values, PDO::PARAM_STR) && $sql_query->execute()) {
                    $result_sql_query = true;
                } elseif (is_array($values)) {
                    $encoded_values = json_encode($values);
                    if (!empty($encoded_values) && $this->BackUpSql($prepare, $encoded_values) && $sql_query->execute($values)) {
                        $result_sql_query = true;
                    }
                }
            } else {
                if ($this->BackUpSql($prepare) && $sql_query->execute()) {
                    $result_sql_query = true;
                }
            }
        }
        if ($result_sql_query) {
            if ($type == 'PLURAL') {
                $data = $sql_query->fetchAll(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    return array('result' => true, 'data' => $data);
                } else {
                    return array('result' => false, 'empty' => true);
                }
            } elseif ($type == 'SINGULAR') {
                $data = $sql_query->fetch(PDO::FETCH_ASSOC);
                if (!empty($data)) {
                    return array('result' => true, 'data' => $data);
                } else {
                    return array('result' => false, 'empty' => true);
                }
            }
        }
        return array('result' => false);
    }
    function Create(string $table, array $inputs)
    {
        do {
            $id = strtolower(strtr(sodium_bin2base64(random_bytes(187), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => '5', '_' => 'w')));
            if (!$this->GET($table, 'id', 'WHERE id=?', $id, 'SINGULAR')['result']) {
                $columns = 'id,';
                $question_marks = '?,';
                $values = array($id);
                foreach ($inputs as $input_name => $input) {
                    $columns .= $input_name . ',';
                    $question_marks .= '?,';
                    $values[] = $input;
                }
                $prepare = 'INSERT INTO ' . $table . ' (' . rtrim($columns, ',') . ') VALUES (' . rtrim($question_marks, ',') . ')';
                $sql_query = $this->prepare($prepare);
                if ($this->BackUpSql($prepare, json_encode($values)) && !empty($sql_query) && $sql_query->execute($values) && $sql_query->rowCount() == 1) {
                    return array('result' => true, 'id' => $id);
                }
                return array('result' => false);
            }
        } while (true);
    }
    function Update(string $table, array $inputs)
    {
        $columns = '';
        $values = [];
        foreach ($inputs as $input_name => $input) {
            $columns .= $input_name . '=?,';
            $values[] = $input;
        }
        $prepare = 'UPDATE ' . $table . ' SET ' . str_replace(',id=?,', '', $columns) . ' WHERE id=?';
        $sql_query = $this->prepare($prepare);
        if ($this->BackUpSql($prepare, json_encode($values)) && !empty($sql_query) && $sql_query->execute($values) && $sql_query->rowCount() == 1) {
            return array('result' => true);
        }
        return array('result' => false);
    }
    function Delete(string $table, string $id)
    {
        $prepare = 'DELETE FROM ' . $table . ' WHERE id=?';
        $sql_query = $this->prepare($prepare);
        if ($this->BackUpSql($prepare, $id) && !empty($sql_query) && $sql_query->bindParam(1, $id, PDO::PARAM_STR) && $sql_query->execute() && $sql_query->rowCount() == 1) {
            return array('result' => true);
        }
        return array('result' => false);
    }
    function Search(string $table, array $columns_to_search, string $columns_to_not_search, string $search_word, string $condition)
    {
        $columns_search = '';
        $search_condition = '';
        $search_input = array();
        foreach ($columns_to_search as $column_to_search) {
            $columns_search .= $column_to_search . ',';
            $search_condition .= $column_to_search . ' LIKE ? OR ';
            $search_input[] = '%' . $search_word . '%';
        }
        if (!empty($columns_to_not_search)) {
            $columns = $columns_search . $columns_to_not_search;
        } else {
            $columns = rtrim($columns_search, ',');
        }
        $prepare = 'SELECT ' . $columns . ' FROM ' . $table . ' WHERE ' . '(' . rtrim($search_condition, ' OR ') . ') ' . $condition;
        $sql_query = $this->prepare($prepare);
        if ($this->BackUpSql($prepare, json_encode($search_input)) && !empty($sql_query) && $sql_query->execute($search_input)) {
            $data = $sql_query->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($data)) {
                return array('result' => true, 'data' => $data);
            }            
        }
        return array('result' => false);
    }

    // function CreateColumn(string $table, string $column_name, string $column_type, string $column_property)
    // {
    //     $prepare = 'ALTER TABLE ' . $table . ' ADD ' . $column_name . ' ' . $column_type . ' ' . $column_property;
    //     $sql_query = $this->prepare($prepare);
    //     if ($this->BackUpSql($prepare) && $sql_query->execute()) {
    //         return array('result' => true);
    //     }
    //     return array('result' => false);
    // }
    // function DeleteColumn(string $table, string $column_name)
    // {
    //     $prepare = 'ALTER TABLE ' . $table . ' DROP ' . $column_name;
    //     $sql_query = $this->prepare($prepare);
    //     if ($this->BackUpSql($prepare) && $sql_query->execute()) {
    //         return array('result' => true);
    //     }
    //     return array('result' => false);
    // }
    // function RenameColumn(string $table, string $old_column, string $new_column)
    // {
    //     $prepare = 'ALTER TABLE ' . $table . ' CHANGE ' . $old_column . ' ' . $new_column . ' mediumint(8)';
    //     $sql_query = $this->prepare($prepare);
    //     if ($this->BackUpSql($prepare) && $sql_query->execute()) {
    //         return array('result' => true);
    //     }
    //     return array('result' => false);
    // }
    // function EmptyColumData(string $table, string $column, string $condition, string $data)
    // {
    //     $prepare = 'UPDATE ' . $table . ' SET ' . $column . '= null ' . $condition;
    //     $sql_query = $this->prepare($prepare);
    //     if ($this->BackUpSql($prepare) && $sql_query->bindParam(1, $data, PDO::PARAM_STR) && $sql_query->execute()) {
    //         return array('result' => true);
    //     }
    //     return array('result' => false);
    // }
}
