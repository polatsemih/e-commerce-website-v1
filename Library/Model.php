<?php
class Model
{
    function __construct()
    {
        $this->database = new Database();
    }
    function Create(string $table_name, array $inputs)
    {
        do {
            $id = strtr(sodium_bin2base64(random_bytes(178) . floor(microtime(true) * 1000), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'V', '_' => 'J'));
            $id_exits = $this->database->GetWithColumnsByStringCondition($table_name, 'id', 'WHERE id=?', $id);
            if (empty($id_exits)) {
                $columns = 'id,';
                $q_mark = '?,';
                $values = array($id);
                foreach ($inputs as $input_name => $input) {
                    $columns .= $input_name . ',';
                    $q_mark .= '?,';
                    $values[] = $input;
                }
                return $this->database->Create($table_name, rtrim($columns, ','), rtrim($q_mark, ','), $values);
            }
        } while (true);
    }
    function CreateAndGetId(string $table_name, array $inputs)
    {
        do {
            $id = strtr(sodium_bin2base64(random_bytes(178) . floor(microtime(true) * 1000), SODIUM_BASE64_VARIANT_URLSAFE_NO_PADDING),  array('-' => 'V', '_' => 'J'));
            $id_exits = $this->database->GetWithColumnsByStringCondition($table_name, 'id', 'WHERE id=?', $id);
            if (empty($id_exits)) {
                $columns = 'id,';
                $q_mark = '?,';
                $values = array($id);
                foreach ($inputs as $input_name => $input) {
                    $columns .= $input_name . ',';
                    $q_mark .= '?,';
                    $values[] = $input;
                }
                $result = $this->database->Create($table_name, rtrim($columns, ','), rtrim($q_mark, ','), $values);
                return array('result' => $result, 'id' => $id);
            }
        } while (true);
    }
    function Update(string $table_name, array $inputs)
    {
        $columns = '';
        $values = [];
        foreach ($inputs as $input_name => $input) {
            $columns .= $input_name . '=?,';
            $values[] = $input;
        }
        return $this->database->Update($table_name, str_replace(',id=?,', '', $columns), $values);
    }
    function Delete(string $table_name, string $id)
    {
        return $this->database->Delete($table_name, $id);
    }
    function Search(string $table_name, array $columns, string $dontsearchcolumns, string $search, string $condition)
    {
        $columns_search = '';
        $search_condition = '';
        $search_input = array();
        foreach ($columns as $column) {
            $columns_search .= $column . ',';
            $search_condition .= $column . ' LIKE ? OR ';
            $search_input[] = '%' . $search . '%';
        }
        if (!empty($dontsearchcolumns)) {
            $new_columns = $columns_search . $dontsearchcolumns;
        } else {
            $new_columns = rtrim($columns_search, ',');
        }
        return $this->database->Search($table_name, $new_columns, '(' . rtrim($search_condition, ' OR') . ') ' . $condition, $search_input);
    }
}
