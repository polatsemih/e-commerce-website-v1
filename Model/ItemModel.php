<?php
class ItemModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetPopularSearchItems()
    {
        return $this->database->Get(TABLE_ITEM, 'item_name,item_url', 'WHERE is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC LIMIT 10', '', 'PLURAL');
    }
    function GetItemByItemCartId(string $columns, string $item_cart_id)
    {
        return $this->database->Get(TABLE_ITEM, $columns, 'WHERE item_cart_id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_cart_id, 'SINGULAR');
    }
    function GetHomeItems()
    {
        return $this->database->Get(TABLE_ITEM, 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE is_item_home=1 AND is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC', '', 'PLURAL');
    }
    function GetItems(string $columns, string $conditions, array $inputs)
    {
        return $this->database->Get(TABLE_ITEM, $columns, $conditions, $inputs, 'PLURAL');
    }
    function GetItemDetails(string $item_url)
    {
        return $this->database->Get(TABLE_ITEM, '*', 'WHERE is_item_for_sale=1 AND item_url=? AND is_item_deleted=0', $item_url, 'SINGULAR');
    }
    function GetRelevantItems(array $inputs)
    {
        return $this->database->Get(TABLE_ITEM, 'item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE (NOT id=?) AND is_item_for_sale=1 AND is_item_deleted=0 AND category=? ORDER BY date_item_created DESC LIMIT 10', $inputs, 'PLURAL');
    }
    function GetFavorite(array $inputs)
    {
        return $this->database->Get(TABLE_ITEM_FAVORITES, 'id', 'WHERE item_id=? AND user_id=? AND is_favorite_removed=0', $inputs, 'SINGULAR');
    }
    function SearchItem(string $search_input)
    {
        return $this->database->Search(TABLE_ITEM, array('item_name', 'item_url'), '', $search_input, 'AND is_item_for_sale=1 AND is_item_deleted=0 ORDER BY date_item_created DESC LIMIT 10', 'PLURAL');
    }
    function GetFavoritesItemId(string $condition, array $inputs)
    {
        return $this->database->Get(TABLE_ITEM_FAVORITES, 'item_id', $condition, $inputs, 'PLURAL');
    }
    function GetFavoriteItem(string $item_id)
    {
        return $this->database->Get(TABLE_ITEM, 'item_cart_id,item_name,item_url,item_price,item_discount_price,item_images_path,item_images', 'WHERE id=? AND is_item_for_sale=1 AND is_item_deleted=0', $item_id, 'SINGULAR');
    }
    function CreateFavorite(array $inputs)
    {
        return $this->database->Create(TABLE_ITEM_FAVORITES, $inputs);
    }
    function UpdateFavorite(array $inputs)
    {
        return $this->database->Update(TABLE_ITEM_FAVORITES, $inputs);
    }
    function GetItemIdByItemUrl(string $item_url)
    {
        return $this->database->Get(TABLE_ITEM, 'id', 'WHERE is_item_for_sale=1 AND item_url=? AND is_item_deleted=0', $item_url, 'SINGULAR');
    }
    function CreateOrder(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER, $inputs);
    }
    function CreateOrder2(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_2, $inputs);
    }
    function CreateOrderBasket(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_BASKET, $inputs);
    }
    function CreateOrder1ConversationError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_1_CONVERSATION_ERROR, $inputs);
    }
    function CreateOrder1StatusError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_1_STATUS_ERROR, $inputs);
    }
    function CreateOrder2ConversationError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_2_CONVERSATION_ERROR, $inputs);
    }
    function CreateOrder2StatusError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_2_STATUS_ERROR, $inputs);
    }
    function GetOrder1LastConversationId(array $inputs)
    {
        return $this->database->Get(TABLE_ORDER, 'conversation_id', 'WHERE user_id=? AND user_ip=? ORDER BY order_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function GetOrders(string $user_id)
    {
        return $this->database->Get(TABLE_ORDER, 'paid_price,basket_id,shipping_contact_name,shipping_city,shipping_country,shipping_address,shipping_zip_code,status,order_created', 'WHERE user_id=? ORDER BY order_created DESC', $user_id, 'PLURAL');
    }
    function GetOrderBasket(string $basket_id)
    {
        return $this->database->Get(TABLE_ORDER_BASKET, 'item_name,item_category,item_size_name,item_quantity,item_discount_price', 'WHERE basket_id=? ORDER BY date_order_basket_created DESC', $basket_id, 'PLURAL');
    }
}
