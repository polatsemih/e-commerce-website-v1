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




    function CreateItem(array $inputs)
    {
        return $this->database->Create(TABLE_ITEM, $inputs);
    }
    function UpdateItem(array $inputs)
    {
        return $this->database->Update(TABLE_ITEM, $inputs);
    }
    function Get(string $columns, string $item_cart_id)
    {
        return $this->database->Get(TABLE_ITEM, $columns, 'WHERE item_cart_id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_cart_id, 'SINGULAR');
    }
}
