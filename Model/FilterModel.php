<?php
class FilterModel extends Model
{

    function __construct()
    {
        parent::__construct();
    }
    function GetSizeBySizeCartId(string $columns, string $size_cart_id)
    {
        return $this->database->Get(TABLE_FILTER_SIZE, $columns, 'WHERE size_cart_id=?', $size_cart_id, 'SINGULAR');
    }
    function GetGenders(string $columns)
    {
        return $this->database->Get(TABLE_FILTER_GENDER, $columns, '', '', 'PLURAL');
    }
    function GetCategories()
    {
        return $this->database->Get(TABLE_FILTER_CATEGORY, 'id,category_name,category_url', '', '', 'PLURAL');
    }
    function GetCategoryById(string $category_id)
    {
        return $this->database->Get(TABLE_FILTER_CATEGORY, 'category_name', 'WHERE id=?', $category_id, 'SINGULAR');
    }
    function GetColors()
    {
        return $this->database->Get(TABLE_FILTER_COLOR, 'id,color_name,color_url,color_hex', '', '', 'PLURAL');
    }
    function GetSizes()
    {
        return $this->database->Get(TABLE_FILTER_SIZE, 'id,size_cart_id,size_name,size_url', '', '', 'PLURAL');
    }
    function GetMaxPrice()
    {
        return $this->database->Get(TABLE_ITEM, 'MAX(item_discount_price)', '', '', 'SINGULAR');
    }
}
