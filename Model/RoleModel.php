<?php
class RoleModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }

    function GetAllRole()
    {
        return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_ROLE, 'id,role_name,role_url,role_date_added,role_date_updated', 'ORDER BY role_date_added ASC');
    }
    function GetRoleById(string $role_id)
    {
        return $this->database->GetWithAllColumnsByStringCondition(TABLE_ROLE, 'WHERE id=?', $role_id);
    }
    function GetRoleByUrl(string $role_url)
    {
        return $this->database->GetWithAllColumnsByStringCondition(TABLE_ROLE, 'WHERE role_url=?', $role_url);
    }
    function GetRoleNameById(string $role_id)
    {
        return $this->database->GetWithColumnsByStringCondition(TABLE_ROLE, 'role_name', 'WHERE id=?', $role_id);
    }
    function GetAllRoleUrl()
    {
        return $this->database->GetAllWithColumnNames(TABLE_ROLE, 'role_url');
    }
    function CreateRole(array $inputs)
    {
        return parent::Create(TABLE_ROLE, $inputs);
    }
    function UpdateRole(array $inputs)
    {
        return parent::Update(TABLE_ROLE, $inputs);
    }
    function DeleteRole(string $id)
    {
        return parent::Delete(TABLE_ROLE, $id);
    }
    function SearchRoles(string $search_input)
    {
        // return parent::Search(TABLE_ROLE, array('id', 'role_name', 'role_url', 'role_date_added', 'role_date_updated'), $search_input);
    }
    function GetRoleNamesAndId()
    {
        return $this->database->GetAllWithColumnsBySimpleCondition(TABLE_ROLE, 'id,role_name', 'ORDER BY role_date_added DESC');
    }
    function EmptyUserRoleColumn(string $column, string $data)
    {
        return $this->database->EmptyColumData(TABLE_USER, $column, 'WHERE ' . $column . ' =?', $data);
    }
}
