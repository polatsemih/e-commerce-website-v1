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
    function GetLogsViewOnce()
    {
        return $this->database->Get(TABLE_LOG_VIEW_ONCE, 'id,user_ip,viewed_page,date_viewed', '', '', 'PLURAL');
    }
    function GetLogsViewAll()
    {
        return $this->database->Get(TABLE_LOG_VIEW_ALL, 'id,user_ip,viewed_page,date_viewed', '', '', 'PLURAL');
    }
    function GetGendersForCount()
    {
        return $this->database->Get(TABLE_FILTER_GENDER, 'gender_name,gender_url', 'ORDER BY date_gender_created ASC', '', 'PLURAL');
    }
    function GetItemsForCount()
    {
        return $this->database->Get(TABLE_ITEM, 'item_name,item_url', 'WHERE is_item_deleted=0', '', 'PLURAL');
    }
    function GetUsersForCount()
    {
        return $this->database->Get(TABLE_USER, 'id', 'WHERE is_user_deleted=0', '', 'PLURAL');
    }
    function GetAdminsForCount()
    {
        return $this->database->Get(TABLE_ADMIN, 'id', '', '', 'PLURAL');
    }
    function GetLogError()
    {
        return $this->database->Get(TABLE_LOG_ERROR, 'user_ip,error_message,date_error_occurred', 'ORDER BY date_error_occurred DESC', '', 'PLURAL');
    }
    function GetLogLogin()
    {
        return $this->database->Get(TABLE_LOG_LOGIN, 'user_id,user_ip,login_success,date_login', 'ORDER BY date_login DESC', '', 'PLURAL');
    }
    function GetLogLoginFail()
    {
        return $this->database->Get(TABLE_LOG_LOGIN_EMAIL_FAIL, 'user_ip,date_fail_login', 'ORDER BY date_fail_login DESC', '', 'PLURAL');
    }
    function GetLogEmailSent()
    {
        return $this->database->Get(TABLE_LOG_EMAIL_SENT, 'user_id,user_ip,email_type,date_email_sent', 'ORDER BY date_email_sent DESC', '', 'PLURAL');
    }
    function GetLogCaptcha()
    {
        return $this->database->Get(TABLE_LOG_CAPTCHA, 'user_ip,success,credit,date_captcha_used', 'ORDER BY date_captcha_used DESC', '', 'PLURAL');
    }
    function GetLogCaptchaTimeout()
    {
        return $this->database->Get(TABLE_CAPTCHA_TIMEOUT, 'user_ip,captcha_error_count,captcha_total_error_count,date_captcha_timeout_expiry,date_captcha_timeout_created', 'ORDER BY date_captcha_timeout_created DESC', '', 'PLURAL');
    }
    function GetUsers(string $condition, $item_bind_params)
    {
        return $this->database->Get(TABLE_USER, 'id,first_name,last_name,email,email_confirmed,is_user_shopped', $condition, $item_bind_params, 'PLURAL');
    }
    function GetUsersDetails(string $user_id)
    {
        return $this->database->Get(TABLE_USER, '*', 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function GetUsersCount($condition, $inputs)
    {
        return $this->database->Get(TABLE_USER, 'id', $condition, $inputs, 'PLURAL');
    }
    function GetLogEmailOrder()
    {
        return $this->database->Get(TABLE_LOG_EMAIL_ORDER, 'email_to,email_message,email_shipping_number,date_email_sent', 'ORDER BY date_email_sent DESC', '', 'PLURAL');
    }
    function GetAdminByAdminId(string $columns, string $user_id)
    {
        return $this->database->Get(TABLE_ADMIN, $columns, 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function UpdateAdmin(array $inputs)
    {
        return $this->database->Update(TABLE_ADMIN, $inputs);
    }
    function GetLogContact()
    {
        return $this->database->Get(TABLE_CONTACT, 'user_ip,first_name,last_name,email,message,date_contact_created', 'ORDER BY date_contact_created DESC', '', 'PLURAL'); 
    }
    function GetOrderInitializeInformations()
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, '*', 'WHERE NOT status=8 ORDER BY date_order_initialize_created DESC', '', 'PLURAL');
    }
    function GetOrderInitializeBasket(string $order_initialize_information_id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_BASKET, '*', 'WHERE order_initialize_information_id=?', $order_initialize_information_id, 'PLURAL');
    }
    function GetOrderPayment(string $order_initialize_information_id)
    {
        return $this->database->Get(TABLE_ORDER_PAYMENT, '*', 'WHERE order_initialize_information_id=?', $order_initialize_information_id, 'SINGULAR');
    }
    function GetOrderPaymentItemTransaction(string $payment_id)
    {
        return $this->database->Get(TABLE_ORDER_PAYMENT_ITEM_TRANSACTION, '*', 'WHERE payment_id=?', $payment_id, 'PLURAL');
    }
    function GetOrderConversationErrors()
    {
        return $this->database->Get(TABLE_ORDER_CONVERSATION_ERROR, 'conversation_id_request,conversation_id_response,system_time,user_id,user_ip,function_type,date_error_occured', 'ORDER BY date_error_occured DESC', '', 'PLURAL');
    }
    function GetOrderStatusErrors()
    {
        return $this->database->Get(TABLE_ORDER_STATUS_ERROR, 'conversation_id_request,conversation_id_response,status,mdStatus,error_code,error_message,error_group,system_time,user_id,user_ip,function_type,date_error_occred', 'ORDER BY date_error_occred DESC', '', 'PLURAL');
    }
    function GetOrderStatusCodes()
    {
        return $this->database->Get(TABLE_ORDER_STATUS, 'status_number,status_message', 'ORDER BY status_number ASC', '', 'PLURAL');
    }
    function GetOrderMDStatusCodes()
    {
        return $this->database->Get(TABLE_ORDER_MD_STATUS, 'md_status_number,md_status_message', 'ORDER BY md_status_number ASC', '', 'PLURAL');
    }
    function GetUserForBlock(string $user_id)
    {
        return $this->database->Get(TABLE_USER, 'is_user_blocked', 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function UpdateUser(array $inputs)
    {
        return $this->database->Update(TABLE_USER, $inputs);
    }
    function GetUserForPastOrders(string $user_id)
    {
        return $this->database->Get(TABLE_USER, 'first_name,last_name', 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function GetOrderInitializeInformationsByUserId(string $user_id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, '*', 'WHERE user_id=? ORDER BY date_order_initialize_created DESC', $user_id, 'PLURAL');
    }
    function GetItemComments()
    {
        return $this->database->Get(TABLE_COMMENT, 'id,user_id,item_id,comment,is_comment_approved,date_comment_approved,date_comment_created,date_comment_last_updated,is_comment_deleted,date_comment_deleted', 'ORDER BY date_comment_created DESC', '', 'PLURAL');
    }
    function GetItemUrlForComment(string $item_id)
    {
        return $this->database->Get(TABLE_ITEM, 'item_name,item_url', 'WHERE id=? AND is_item_deleted=0', $item_id, 'SINGULAR');
    }
    function GetItemCommentReply(string $comment_id)
    {
        return $this->database->Get(TABLE_COMMENT_REPLY, 'id,user_id,comment_reply,is_comment_reply_approved,date_comment_reply_approved,date_comment_reply_created,date_comment_reply_last_updated,is_comment_reply_deleted,date_comment_reply_deleted', 'WHERE comment_id=? ORDER BY date_comment_reply_created DESC', $comment_id, 'PLURAL');
    }
    function GetItemForComment(string $item_url)
    {
        return $this->database->Get(TABLE_ITEM, 'id,item_name', 'WHERE item_url=?', $item_url, 'SINGULAR');
    }
    function GetItemCommentsByItemId(string $item_id)
    {
        return $this->database->Get(TABLE_COMMENT, 'id,user_id,item_id,comment,is_comment_approved,date_comment_approved,date_comment_created,date_comment_last_updated,is_comment_deleted,date_comment_deleted', 'WHERE item_id=? ORDER BY date_comment_created DESC', $item_id, 'PLURAL');
    }
    function GetCountViewOnceIpForIndex(int $past_day)
    {
        return $this->database->Get(TABLE_LOG_VIEW_DAILY_IP, 'COUNT(id)', 'WHERE date_viewed = CURDATE() - ' . $past_day, '', 'SINGULAR');
    }
    function GetUserComment(string $user_id)
    {
        return $this->database->Get(TABLE_COMMENT, 'id,user_id,item_id,comment,is_comment_approved,date_comment_approved,date_comment_created,date_comment_last_updated,is_comment_deleted,date_comment_deleted', 'WHERE user_id=? ORDER BY date_comment_created DESC', $user_id, 'PLURAL');
    }
    function GetUserName(string $user_id)
    {
        return $this->database->Get(TABLE_USER, 'first_name,last_name', 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function GetOrderInitializeInformationById(string $id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, '*', 'WHERE id=?', $id, 'SINGULAR');
    }
    function UpdateOrderInitializeInformations(array $inputs)
    {
        return $this->database->Update(TABLE_ORDER_INITIALIZE_INFORMATIONS, $inputs);
    }
    function GetOrderInitializeInformationForSendEmail(string $id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_INFORMATIONS, 'user_id,user_email,user_identity_number,user_phone_number,shipping_contact_name,shipping_city,shipping_country,shipping_address,status', 'WHERE id=?', $id, 'SINGULAR');
    }
    function GetOrderInitializeBasketForSendEmail(string $order_initialize_information_id)
    {
        return $this->database->Get(TABLE_ORDER_INITIALIZE_BASKET, 'item_name,item_size_name,item_quantity', 'WHERE order_initialize_information_id=?', $order_initialize_information_id, 'PLURAL');
    }
    function CreateLogEmailOrderAdmin(array $inputs)
    {
        return $this->database->Create(TABLE_LOG_EMAIL_ORDER, $inputs);
    }
    function GetUserForSendMail(string $user_email)
    {
        return $this->database->Get(TABLE_USER, 'first_name,last_name', 'WHERE email=?', $user_email, 'SINGULAR');
    }
}
