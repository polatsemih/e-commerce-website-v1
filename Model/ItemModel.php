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
    function CreateOrderInitializeInformations(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_INITIALIZE_INFORMATIONS, $inputs);
    }
    function CreateOrderInitializeBasket(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_INITIALIZE_BASKET, $inputs);
    }
    function CreateOrderStatusError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_STATUS_ERROR, $inputs);
    }
    function CreateOrderConversationError(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_CONVERSATION_ERROR, $inputs);
    }
    function GetOrderInitializeLastConversationId(array $inputs)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, 'conversation_id', 'WHERE user_ip=? AND user_id=? ORDER BY date_order_initialize_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function CreateOrderVerify(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_VERIFY, $inputs);
    }
    function CreateOrderPayment(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_PAYMENT, $inputs);
    }
    function CreateOrderPaymentItemTransaction(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_PAYMENT_ITEM_TRANSACTION, $inputs);
    }
    function GetItemForOrderEmail(array $inputs)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_BASKET, 'item_name,item_size_name,item_size_url,item_quantity', 'WHERE order_initialize_information_id=? AND item_id=?', $inputs, 'SINGULAR');
    }
    function GetItemForOrder(string $item_size_url, string $item_id)
    {
        return $this->database->Get(TABLE_ITEM, 'item_total_quantity,' . $item_size_url, 'WHERE id=?', $item_id, 'SINGULAR');
    }
    function UpdateItem(array $inputs)
    {
        return $this->database->Update(TABLE_ITEM, $inputs);
    }
    function GetOrders(string $user_id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, 'id,paid_price,shipping_contact_name,shipping_city,shipping_country,shipping_address,status,date_order_initialize_created', 'WHERE user_id=? AND NOT status=8 ORDER BY date_order_initialize_created DESC', $user_id, 'PLURAL');
    }
    function UpdateOrder(array $inputs)
    {
        return $this->database->Update(TABLE_ORDER_INITIALIZE_INFORMATIONS, $inputs);
    }
    function GetOrderBasket(string $order_initialize_information_id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_BASKET, 'item_name,item_category,item_size_name,item_quantity,item_discount_price', 'WHERE order_initialize_information_id=? ORDER BY date_order_initialize_basket_created DESC', $order_initialize_information_id, 'PLURAL');
    }
    function CreateOrderInstallment(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_INSTALLMENT, $inputs);
    }
    function CreateOrderInstallmentPrices(array $inputs)
    {
        return $this->database->Create(TABLE_ORDER_INSTALLMENT_PRICES, $inputs);
    }
}
