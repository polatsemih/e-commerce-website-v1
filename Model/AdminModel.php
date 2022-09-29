<?php
class AdminModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetItems(string $item_conditions, $item_bind_params)
    {
        return $this->database->Get(TABLE_ITEM, 'id,is_item_home,is_item_for_sale,item_name,item_url,item_price,item_discount_price,item_images_path,item_images,item_total_quantity', $item_conditions, $item_bind_params, 'PLURAL');
    }
    function GetItemsCount()
    {
        return $this->database->Get(TABLE_ITEM, 'COUNT(id)', 'WHERE is_item_deleted=0', '', 'SINGULAR');
    }
    function GetSizes()
    {
        return $this->database->Get(TABLE_FILTER_SIZE, 'size_name,size_url', 'ORDER BY date_size_created ASC', '', 'PLURAL');
    }
    function GetGenders()
    {
        return $this->database->Get(TABLE_FILTER_GENDER, 'id,gender_name,gender_url', 'ORDER BY date_gender_created ASC', '', 'PLURAL');
    }
    function GetCategories()
    {
        return $this->database->Get(TABLE_FILTER_CATEGORY, 'id,category_name,category_url', 'ORDER BY date_category_created ASC', '', 'PLURAL');
    }
    function GetColors()
    {
        return $this->database->Get(TABLE_FILTER_COLOR, 'id,color_name,color_url', 'ORDER BY date_color_created ASC', '', 'PLURAL');
    }
    function IsItemImagePathUnique(string $item_images_path)
    {
        return $this->database->GET(TABLE_ITEM, 'id', 'WHERE item_images_path=?', $item_images_path, 'SINGULAR');
    }
    function IsItemCartIdUnique(string $item_cart_id)
    {
        return $this->database->GET(TABLE_ITEM, 'id', 'WHERE item_cart_id=?', $item_cart_id, 'SINGULAR');
    }
    function CreateItem(array $inputs)
    {
        return $this->database->Create(TABLE_ITEM, $inputs);
    }
    function GetItemDetails(string $item_url)
    {
        return $this->database->Get(TABLE_ITEM, '*', 'WHERE item_url=? AND is_item_deleted=0', $item_url, 'SINGULAR');
    }
    function IsItemUrlUnique(string $item_url)
    {
        return $this->database->GET(TABLE_ITEM, 'id', 'WHERE item_url=?', $item_url, 'SINGULAR');
    }
    function GetItemForUpdate(string $item_url)
    {
        return $this->database->Get(TABLE_ITEM, 'id,item_url,item_images_path,item_images', 'WHERE item_url=? AND is_item_deleted=0', $item_url, 'SINGULAR');
    }
    function UpdateItem(array $inputs)
    {
        return $this->database->Update(TABLE_ITEM, $inputs);
    }
}
