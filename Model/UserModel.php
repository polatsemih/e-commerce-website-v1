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


    
    function GetUserByEmail(string $columns, string $user_email)
    {
        return $this->database->Get(TABLE_USER, $columns, 'WHERE email=? AND is_user_deleted=0', $user_email, 'SINGULAR');
    }
    function CreateUser(array $inputs)
    {
        return $this->database->Create(TABLE_USER, $inputs);
    }
    function UpdateUser(array $inputs)
    {
        return $this->database->Update(TABLE_USER, $inputs);
    }
    function DeleteUser(string $user_id)
    {
        return $this->database->Delete(TABLE_USER, $user_id);
    }
    function IsUserEmailUnique(string $user_email)
    {
        return $this->database->Get(TABLE_USER, 'id', 'WHERE email=? AND is_user_deleted=0 ', $user_email, 'PLURAL');
    }
}
