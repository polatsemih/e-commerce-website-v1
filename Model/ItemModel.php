<?php
class ItemModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    // ItemDetails
    function GetItemDetails(string $item_url)
    {
        return $this->db->GetWithAllColumnsByStringCondition(TABLE_ITEM, 'WHERE item_url=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_url);
    }
    function GetRelevantItems(array $inputs)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_ITEM, 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE category=? AND is_item_for_sale=1 AND is_item_deleted=0 AND (NOT id=?) LIMIT 10', $inputs);
    }
    // Comment
    function GetItemIdByUrl(string $item_url)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'id', 'WHERE item_url=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_url);
    }















    function GetItems(string $columns, string $conditions, array $inputs)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_ITEM, $columns, $conditions, $inputs);
    }
    function GetMinAndMaxPrices()
    {
        return $this->db->GetWithColumns(TABLE_ITEM, 'MAX(item_discount_price)');
    }
















    function GetItemsByGender(string $columns, string $gender_id)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_ITEM, $columns, 'WHERE gender_id=?', $gender_id);
    }
    function GetItemsByFilters(string $columns, string $conditions, array $inputs)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_ITEM, $columns, $conditions, $inputs);
    }















    function GetItemsDiscountPrices()
    {
        return $this->db->GetAllWithColumns(TABLE_ITEM, 'item_discount_price');
    }
    function CountItems()
    {
        return $this->db->GetWithColumns(TABLE_ITEM, 'COUNT(id)');
    }
    function CountItemsByCondition(string $condition)
    {
        return $this->db->GetWithColumnsBySimpleCondition(TABLE_ITEM, 'COUNT(id)', $condition);
    }
    function CountItemsByConditionAndData(string $condition, array $datas)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'COUNT(id)', $condition, $datas);
    }
    function GetItemsByCondition(string $condition)
    {
        return $this->db->GetAllWithColumnsBySimpleCondition(TABLE_ITEM, 'id,item_insale,item_name,item_url,item_price,item_discount_price,item_total_number,item_images,item_date_added', $condition);
    }
    function GetItemsByConditionAndData(string $condition, array $datas)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_ITEM, 'id,item_insale,item_name,item_url,item_price,item_discount_price,item_total_number,item_images,item_date_added', $condition, $datas);
    }
    function GetCommentsByConditionAndData(string $condition, array $datas)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_COMMENT, 'id,user_id,item_id,comment,comment_approved,comment_date_added,comment_date_updated', $condition, $datas);
    }
    function CountComments(string $id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_COMMENT, 'COUNT(id)', 'WHERE item_id=?', $id);
    }
    function CountCommentsByConditionAndData(string $condition, array $datas)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_COMMENT, 'COUNT(id)', $condition, $datas);
    }
    function GetItemForAdminUpdate(string $item_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_images,item_date_added,item_date_updated', 'WHERE id=?', $item_id);
    }
    function GetItemImagesForAdminUpdate(string $item_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_images', 'WHERE id=?', $item_id);
    }


    function GetItemByUrlForComment(string $item_url)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'id,item_name,item_url', 'WHERE item_url=?', $item_url);
    }
    function CreateItem(array $inputs)
    {
        $columns = '';
        $q_mark = '';
        $values = array();
        foreach ($inputs as $input_name => $input) {
            $columns .= $input_name . ',';
            $q_mark .= '?,';
            $values[] = $input;
        }
        return $this->db->Create(TABLE_ITEM, rtrim($columns, ','), rtrim($q_mark, ','), $values);
    }
    function UpdateItem(array $inputs)
    {
        return parent::Update(TABLE_ITEM, $inputs);
    }
    function DeleteItem(string $id)
    {
        return parent::Delete(TABLE_ITEM, $id);
    }



    function GetAllItemWithSubFilter(string $filter_sub_name)
    {
        return $this->db->GetAllWithColumnNames(TABLE_ITEM, 'id,item_name,item_url,item_price,item_discount_price,item_total_number,' . $filter_sub_name);
    }
    function GetItemNameAndUrlById(string $item_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_name,item_url', 'WHERE id=?', $item_id);
    }


    function GetItemById(string $columns, string $item_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_ITEM, $columns, 'WHERE id=?', $item_id);
    }
    function GetItemBySubFilter(string $filter_item_column, string $filter_sub_id)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_ITEM, 'id,item_name,item_url,item_images,item_price,item_discount_price,item_total_number,' . $filter_item_column, 'WHERE ' . $filter_item_column . '=?', $filter_sub_id);
    }
}
