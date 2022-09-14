<?php
class UserModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetUserByUserId(string $columns, string $user_id)
    {
        return $this->database->Get(TABLE_USER, $columns, 'WHERE id=? AND is_user_deleted=0', $user_id, 'SINGULAR');
    }
    function IsUserEmailUnique(string $user_email)
    {
        return $this->database->Get(TABLE_USER, 'COUNT(id)', 'WHERE email=? AND is_user_deleted=0 ', $user_email, 'SINGULAR');
    }
    function CreateUser(array $inputs)
    {
        return $this->database->Create(TABLE_USER, $inputs);
    }
    function UpdateUser(array $inputs)
    {
        return $this->database->Update(TABLE_USER, $inputs);
    }
    function GetUserByEmail(string $columns, string $user_email)
    {
        return $this->database->Get(TABLE_USER, $columns, 'WHERE email=? AND is_user_deleted=0', $user_email, 'SINGULAR');
    }
    function CreateSessionUpdateEmail(array $inputs)
    {
        return $this->database->Create(TABLE_SESSION_UPDATE_EMAIL, $inputs);
    }
    function GetSessionUpdateEmail(array $inputs)
    {
        return $this->database->Get(TABLE_SESSION_UPDATE_EMAIL, 'id,user_id,update_email_hashed_tokens,date_update_email_expiry,is_update_email_used', 'WHERE user_ip=? AND update_email_token=? ORDER BY date_update_email_created DESC LIMIT 1', $inputs, 'SINGULAR');
    }
    function UpdateSessionUpdateEmail(array $inputs)
    {
        return $this->database->Update(TABLE_SESSION_UPDATE_EMAIL, $inputs);
    }
    function GetAddress(string $user_id)
    {
        return $this->database->Get(TABLE_ADDRESS, 'id,address', 'WHERE user_id=? ORDER BY address_created DESC', $user_id, 'PLURAL');
    }
    function CreateAddress(array $inputs)
    {
        return $this->database->Create(TABLE_ADDRESS, $inputs);
    }
    function UpdateAddress(array $inputs)
    {
        return $this->database->Update(TABLE_ADDRESS, $inputs);
    }
    function GetUserAdminByEmail(string $columns, string $user_email)
    {
        return $this->database->Get(TABLE_ADMIN, $columns, 'WHERE email=?', $user_email, 'SINGULAR');
    }
    function GetAdminByAdminId(string $columns, string $user_id)
    {
        return $this->database->Get(TABLE_ADMIN, $columns, 'WHERE id=?', $user_id, 'SINGULAR');
    }
    function UpdateAdmin(array $inputs)
    {
        return $this->database->Update(TABLE_ADMIN, $inputs);
    }
}
