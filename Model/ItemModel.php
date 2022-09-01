<?php
class ItemModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetHomeItems()
    {
        return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_ITEM, 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE is_item_home=1 AND is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC');
    }
    function GetItems(string $columns, string $conditions, array $inputs)
    {
        return $this->database->GetAllWithColumnsByArrayCondition(TABLE_ITEM, $columns, $conditions, $inputs);
    }
    function GetItemDetails(string $item_url)
    {
        return $this->database->GetWithAllColumnsByStringCondition(TABLE_ITEM, 'WHERE item_url=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_url);
    }
    function GetFavorite(array $inputs)
    {
        return $this->database->GetWithColumnsByArrayCondition(TABLE_ITEM_FAVORITES, 'id', 'WHERE item_id=? AND user_id=? AND is_favorite_removed=0', $inputs);
    }
    function GetRelevantItems(array $inputs)
    {
        return $this->database->GetAllWithColumnsByArrayCondition(TABLE_ITEM, 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE category=? AND is_item_for_sale=1 AND is_item_deleted=0 AND (NOT id=?) ORDER BY date_item_created DESC LIMIT 10', $inputs);
    }
    function GetItemIdByUrl(string $item_url)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'id', 'WHERE item_url=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_url);
    }
    function GetFavoritesItemId(string $condition, array $inputs)
    {
        return $this->database->GetAllWithColumnsByArrayCondition(TABLE_ITEM_FAVORITES, 'item_id', $condition, $inputs);
    }
    function GetFavoriteItem(string $item_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_cart_id,item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_id);
    }
    function GetItemIdByItemCartId(string $item_cart_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'id,item_cart_id', 'WHERE item_cart_id=?', $item_cart_id);
    }
    function CreateFavorite(array $inputs)
    {
        return parent::Create(TABLE_ITEM_FAVORITES, $inputs);
    }
    function UpdateFavorite(array $inputs)
    {
        return parent::Update(TABLE_ITEM_FAVORITES, $inputs);
    }
    function ConfirmItemByItemCartId(string $size_url, string $item_cart_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_cart_id,' . $size_url, 'WHERE item_cart_id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_cart_id);
    }
    function ConfirmSizeBySizeCartId(string $size_cart_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_FILTER_SIZE, 'size_cart_id,size_url', 'WHERE size_cart_id=?', $size_cart_id);
    }
    function GetSizeBySizeCartIdForConstructor(string $size_cart_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_FILTER_SIZE, 'size_cart_id,size_name,size_url', 'WHERE size_cart_id=?', $size_cart_id);
    }
    function GetItemByItemCartIdForConstructor(string $size_url, string $item_cart_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_cart_id,item_name,item_url,item_images_path,item_images,item_price,item_discount_price,' . $size_url, 'WHERE item_cart_id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_cart_id);
    }
    function SearchItem(string $search_input)
    {
        return parent::Search(TABLE_ITEM, array('item_name', 'item_url'), '', $search_input, 'AND is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC LIMIT 10');
    }
    function GetSearchPopularItems()
    {
        return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_ITEM, 'item_name,item_url', 'WHERE is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC LIMIT 5');
    }



























    // function GetItemsByGender(string $columns, string $gender_id)
    // {
    //     return $this->database->GetAllWithColumnsByStringCondition(TABLE_ITEM, $columns, 'WHERE gender_id=?', $gender_id);
    // }
    // function GetItemsByFilters(string $columns, string $conditions, array $inputs)
    // {
    //     return $this->database->GetAllWithColumnsByArrayCondition(TABLE_ITEM, $columns, $conditions, $inputs);
    // }
    // function GetItemsDiscountPrices()
    // {
    //     return $this->database->GetAllWithColumns(TABLE_ITEM, 'item_discount_price');
    // }
    // function CountItems()
    // {
    //     return $this->database->GetWithColumns(TABLE_ITEM, 'COUNT(id)');
    // }
    // function CountItemsByCondition(string $condition)
    // {
    //     return $this->database->GetWithColumnsBySimpleCondition(TABLE_ITEM, 'COUNT(id)', $condition);
    // }
    // function CountItemsByConditionAndData(string $condition, array $datas)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'COUNT(id)', $condition, $datas);
    // }
    // function GetItemsByCondition(string $condition)
    // {
    //     return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_ITEM, 'id,item_insale,item_name,item_url,item_price,item_discount_price,item_total_number,item_images,item_date_added', $condition);
    // }
    // function GetItemsByConditionAndData(string $condition, array $datas)
    // {
    //     return $this->database->GetAllWithColumnsByArrayCondition(TABLE_ITEM, 'id,item_insale,item_name,item_url,item_price,item_discount_price,item_total_number,item_images,item_date_added', $condition, $datas);
    // }
    // function GetCommentsByConditionAndData(string $condition, array $datas)
    // {
    //     return $this->database->GetAllWithColumnsByArrayCondition(TABLE_COMMENT, 'id,user_id,item_id,comment,comment_approved,comment_date_added,comment_date_updated', $condition, $datas);
    // }
    // function CountComments(string $id)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_COMMENT, 'COUNT(id)', 'WHERE item_id=?', $id);
    // }
    // function CountCommentsByConditionAndData(string $condition, array $datas)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_COMMENT, 'COUNT(id)', $condition, $datas);
    // }
    // function GetItemForAdminUpdate(string $item_id)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_images,item_date_added,item_date_updated', 'WHERE id=?', $item_id);
    // }
    // function GetItemImagesForAdminUpdate(string $item_id)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_images', 'WHERE id=?', $item_id);
    // }
    // function GetItemByUrlForComment(string $item_url)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'id,item_name,item_url', 'WHERE item_url=?', $item_url);
    // }
    // function CreateItem(array $inputs)
    // {
    //     $columns = '';
    //     $q_mark = '';
    //     $values = array();
    //     foreach ($inputs as $input_name => $input) {
    //         $columns .= $input_name . ',';
    //         $q_mark .= '?,';
    //         $values[] = $input;
    //     }
    //     return $this->database->Create(TABLE_ITEM, rtrim($columns, ','), rtrim($q_mark, ','), $values);
    // }
    // function UpdateItem(array $inputs)
    // {
    //     return parent::Update(TABLE_ITEM, $inputs);
    // }
    // function DeleteItem(string $id)
    // {
    //     return parent::Delete(TABLE_ITEM, $id);
    // }
    // function GetAllItemWithSubFilter(string $filter_sub_name)
    // {
    //     return $this->database->GetAllWithColumnNames(TABLE_ITEM, 'id,item_name,item_url,item_price,item_discount_price,item_total_number,' . $filter_sub_name);
    // }
    // function GetItemNameAndUrlById(string $item_id)
    // {
    //     return $this->database->GetWithColumnsByStringCondition(TABLE_ITEM, 'item_name,item_url', 'WHERE id=?', $item_id);
    // }
    // function GetItemBySubFilter(string $filter_item_column, string $filter_sub_id)
    // {
    //     return $this->database->GetAllWithColumnsByStringCondition(TABLE_ITEM, 'id,item_name,item_url,item_images,item_price,item_discount_price,item_total_number,' . $filter_item_column, 'WHERE ' . $filter_item_column . '=?', $filter_sub_id);
    // }
}
