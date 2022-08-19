<?php
class FilterModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }
    // ItemDetails
    function GetSizes()
    {
        return $this->db->GetAllWithColumns(TABLE_FILTER_SIZE, 'id,size_cart_id,size_name,size_url');
    }








    function GetAllGenders(string $columns)
    {
        return $this->db->GetAllWithColumns(TABLE_FILTER_GENDER, $columns);
    }
    function GetAllCategories()
    {
        return $this->db->GetAllWithColumns(TABLE_FILTER_CATEGORY, 'id,category_name,category_url');
    }
    function GetAllColors()
    {
        return $this->db->GetAllWithColumns(TABLE_FILTER_COLOR, 'id,color_name,color_url,color_hex');
    }
    function GetLogViewGender(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_LOG_VIEWS_GENDER, 'id', 'WHERE gender_id=? AND user_ip=?', $inputs);
    }
    function CreateLogViewGender(array $inputs)
    {
        return parent::Create(TABLE_LOG_VIEWS_GENDER, $inputs);
    }









    // function GetSubFiltersByMainFilterId(string $columns, string $id) {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_SUB_FILTER, $columns, 'WHERE main_filter_id=?', $id);
    // }

    // function GetAllMainFilters(string $columns) {
    //     return $this->db->GetAllWithColumns(TABLE_MAIN_FILTER, $columns);
    // }



    // function GetMainFilterByUrl(string $columns, string $main_filter_url) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_MAIN_FILTER, $columns, 'WHERE main_filter_url=? AND main_filter_has_sub_filter=1', $main_filter_url);
    // }
    // function GetSubFilterByUrl(string $columns, string $sub_filter_url) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_SUB_FILTER, $columns, 'WHERE sub_filter_url=?', $sub_filter_url);
    // }










    // // home
    // function GetFiltersForAdminItem() {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_MAIN_FILTER, 'id,filter_name,filter_type', 'WHERE filter_has_sub=? ORDER BY filter_type ASC, filter_date_added ASC', '1');
    // }
    // function GetSubFiltersByFilterIdForAdminItem(string $filter_id) {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_SUB_FILTER, 'id,filter_sub_name', 'WHERE filter_id=? ORDER BY filter_sub_date_added ASC', $filter_id);
    // }










    // function GetFilters() {
    //     return $this->db->GetWithAllColumnsByStringCondition(TABLE_MAIN_FILTER, 'ORDER BY filter_type ASC');
    // }
    // function GetAllFilterWithName() {
    //     return $this->db->GetAllWithColumnNames(TABLE_MAIN_FILTER, 'filter_name');
    // }
    // function CreateFilter(array $inputs) {
    //     return parent::Create(TABLE_MAIN_FILTER, $inputs);
    // }
    // function UpdateFilter(array $inputs) {
    //     return parent::Update(TABLE_MAIN_FILTER, $inputs);
    // }
    // function DeleteFilter(string $id) {
    //     return parent::Delete(TABLE_MAIN_FILTER, $id);
    // }
    // function SearchFilters(string $search_input) {
    //     // return parent::Search(TABLE_MAIN_FILTER, array('id', 'filter_name', 'filter_url', 'filter_type', 'filter_date_added', 'filter_date_updated'), $search_input);
    // }
    // function GetFilterSubById(string $filter_id) {
    //     return $this->db->GetWithAllColumnsByStringCondition(TABLE_SUB_FILTER, 'WHERE id=?', $filter_id);
    // }
    // function GetSubFilterWithIdByName(string $filter_sub_name) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_SUB_FILTER, 'id', 'WHERE filter_sub_name=?', $filter_sub_name);
    // }
    // function GetFilterSubNameById(string $id) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_SUB_FILTER, 'filter_sub_name', 'WHERE id=?', $id);
    // }
    // function GetAllSubFilterWithName() {
    //     return $this->db->GetAllWithColumnNames(TABLE_SUB_FILTER, 'filter_sub_name');
    // }
    // function GetValidFilterById(string $filter_id) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_MAIN_FILTER, 'filter_has_sub', 'WHERE id=?', $filter_id);
    // }
    // function UpdateFilterValidation(array $filter_update) {
    //     return parent::Update(TABLE_MAIN_FILTER, $filter_update);
    // }
    // function UpdateFilterHasNoSub(array $filter_update) {
    //     return parent::Update(TABLE_MAIN_FILTER, $filter_update);
    // }
    // function CreateFilterSub(array $inputs) {
    //     return parent::Create(TABLE_SUB_FILTER, $inputs);
    // }
    // function UpdateFilterSub(array $inputs) {
    //     return parent::Update(TABLE_SUB_FILTER, $inputs);
    // }
    // function DeleteFilterSub(string $id) {
    //     return parent::Delete(TABLE_SUB_FILTER, $id);
    // }
    // function CreateItemColumn(string $input) {
    //     return $this->db->CreateColumn(TABLE_ITEM, $input, 'VARCHAR(50)', '');
    // }
    // function CreateItemColumnCountable(string $input) {
    //     return $this->db->CreateColumn(TABLE_ITEM, $input, 'MEDIUMINT(8)', 'NOT NULL');
    // }
    // function DeleteItemColumn(string $inputs) {
    //     return $this->db->DeleteColumn(TABLE_ITEM, $inputs);
    // }
    // function RenameItemColumn(string $old_column, string $new_column) {
    //     return $this->db->RenameColumn(TABLE_ITEM, $old_column, $new_column);
    // }
    // function EmptyItemColumn(string $column, string $data) {
    //     return $this->db->EmptyColumData(TABLE_ITEM, $column, 'WHERE '.$column.' =?', $data);
    // }


    // function GetSubFiltersByFilterId(string $columns, string $id) {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_SUB_FILTER, $columns, 'WHERE filter_id=? ORDER BY filter_sub_date_added ASC', $id);
    // }
    // function GetAllValidFilter(string $columns) {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_MAIN_FILTER, $columns, 'WHERE filter_has_sub=? ORDER BY filter_type ASC', '1');
    // }
    // function GetFilterById(string $columns, string $filter_id) {
    //     return $this->db->GetWithColumnsByStringCondition(TABLE_MAIN_FILTER, $columns, 'WHERE id=?', $filter_id);
    // }
    // function GetFilterSubByFilterId(string $columns, string $id) {
    //     return $this->db->GetAllWithColumnsByStringCondition(TABLE_SUB_FILTER, $columns, 'WHERE filter_id=? ORDER BY filter_sub_date_added ASC', $id);
    // }
}
