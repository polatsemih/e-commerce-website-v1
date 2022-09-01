<?php
class UserModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetUser(string $columns, string $user_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_USER, $columns, 'WHERE id=? AND is_user_deleted=0', $user_id);
    }




















    // Profile
    function UpdateUser(array $inputs)
    {
        return parent::Update(TABLE_USER, $inputs);
    }






















    

    





















    // home
    function GetCommentUserByUserId(string $user_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_USER, 'first_name,last_name,profile_image', 'WHERE id=?', $user_id);
    }




    function GetAllUser()
    {
        return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_USER, 'id,first_name,last_name,email,email_confirmed,tel,tel_confirmed', 'WHERE email_confirmed=1 ORDER BY date_registration DESC');
    }
    function GetUserEmailById(string $user_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_USER, 'email', 'WHERE id=?', $user_id);
    }
    function GetUsersEmails()
    {
        return $this->database->GetAllWithColumnNames(TABLE_USER, 'email');
    }
    function GetUsersPhoneNumbers()
    {
        return $this->database->GetAllWithColumnNames(TABLE_USER, 'tel');
    }
    function CreateUser(array $inputs)
    {
        $columns = '';
        $q_mark = '';
        $values = array();
        foreach ($inputs as $input_name => $input) :
            $columns .= $input_name . ',';
            $q_mark .= '?,';
            $values[] = $input;
        endforeach;
        return $this->database->Create(TABLE_ITEM, rtrim($columns, ','), rtrim($q_mark, ','), $values);
    }
    function GetUserPassword(string $user_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_USER, 'user_password', 'WHERE id=?', $user_id);
    }
    function DeleteUser(string $id)
    {
        return parent::Delete(TABLE_USER, $id);
    }
    function SearchUsers(string $search_input)
    {
        // return parent::Search(TABLE_USER, array('id', 'first_name', 'last_name', 'email', 'email_confirmed', 'tel', 'tel_confirmed'), $search_input);
    }
    function GetCommentsByUserId(string $user_id)
    {
        return $this->database->GetAllWithColumnsByStringCondition(TABLE_COMMENT, 'id,user_id,item_id,comment,comment_date_added,comment_date_updated', 'WHERE user_id=? ORDER BY comment_date_added DESC', $user_id);
    }
    function GetAdvertisingInfos()
    {
        return $this->database->GetAllWithColumnsByArrayCondition(TABLE_USER, 'first_name,last_name,email,tel', 'WHERE email_confirmed=? AND tel_confirmed=? ORDER BY date_registration ASC', array(1, 1));
    }
    function GetUsersByRole(string $role_id)
    {
        return $this->database->GetAllWithColumnsByStringCondition(TABLE_USER, 'id,first_name,last_name,email,email_confirmed,tel,tel_confirmed', 'WHERE user_role=? AND email_confirmed=1 ORDER BY date_registration DESC', $role_id);
    }
}
