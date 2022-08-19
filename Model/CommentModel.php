<?php
class CommentModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    // ItemDetails
    function GetCommentsForAdminByItemId(string $item_id, string $condition)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT, 'id,user_id,comment,is_comment_approved,date_comment_created', $condition, $item_id);
    }
    function GetCommentsCountForAdminByItemId(string $item_id, string $condition)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT, 'id', $condition, $item_id);
    }
    function GetCommentsReplyForAdminByCommentId(String $comment_id)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT_REPLY, 'id,comment_id,user_id,comment_reply,is_comment_reply_approved,date_comment_reply_created', 'WHERE comment_id=? AND is_comment_reply_deleted=0 ORDER BY date_comment_reply_created ASC', $comment_id);
    }
    function GetCommentsForUserByItemId(array $inputs, string $condition)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_COMMENT, 'id,user_id,comment,date_comment_created', $condition, $inputs);
    }
    function GetCommentsCountForUserByItemId(array $inputs, string $condition)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_COMMENT, 'id', $condition, $inputs);
    }
    function GetCommentsReplyForUserByCommentId(array $inputs)
    {
        return $this->db->GetAllWithColumnsByArrayCondition(TABLE_COMMENT_REPLY, 'id,comment_id,user_id,comment_reply,date_comment_reply_created', 'WHERE comment_id=? AND is_comment_reply_deleted=0 AND ((user_id=? AND is_comment_reply_approved=0) OR is_comment_reply_approved=1) ORDER BY date_comment_reply_created ASC', $inputs);
    }
    function GetCommentsForAnonymousByItemId(string $item_id, string $condition)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT, 'id,user_id,comment,date_comment_created', $condition, $item_id);
    }
    function GetCommentsCountForAnonymousByItemId(string $item_id, string $condition)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT, 'id', $condition, $item_id);
    }
    function GetCommentsReplyForAnonymousByCommentId(string $comment_id)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT_REPLY, 'id,comment_id,user_id,comment_reply,date_comment_reply_created', 'WHERE comment_id=? AND is_comment_reply_deleted=0 AND is_comment_reply_approved=1 ORDER BY date_comment_reply_created ASC', $comment_id);
    }
    // Comment
    function GetCommentById(string $comment_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_COMMENT, 'id,user_id,comment,date_comment_created', 'WHERE id=? AND is_comment_deleted=0', $comment_id);
    }
    function GetCommentReplyById(string $comment_reply_id)
    {
        return $this->db->GetWithColumnsByStringCondition(TABLE_COMMENT_REPLY, 'id,comment_id,user_id,comment_reply,date_comment_reply_created', 'WHERE id=? AND is_comment_reply_deleted=0', $comment_reply_id);
    }
    function ConfirmComment(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_COMMENT, 'id', 'WHERE id=? AND user_id=? AND is_comment_deleted=0', $inputs);
    }
    function ConfirmCommentReply(array $inputs)
    {
        return $this->db->GetWithColumnsByArrayCondition(TABLE_COMMENT_REPLY, 'id,comment_id', 'WHERE id=? AND comment_id=? AND user_id=? AND is_comment_reply_deleted=0', $inputs);
    }
    function CreateComment(array $inputs)
    {
        return parent::Create(TABLE_COMMENT, $inputs);
    }
    function CreateCommentReply(array $inputs)
    {
        return parent::Create(TABLE_COMMENT_REPLY, $inputs);
    }
    function UpdateComment(array $inputs)
    {
        return parent::Update(TABLE_COMMENT, $inputs);
    }
    function UpdateCommentReply(array $inputs)
    {
        return parent::Update(TABLE_COMMENT_REPLY, $inputs);
    }
    function CommentHasReply(string $comment_id)
    {
        return $this->db->GetAllWithColumnsByStringCondition(TABLE_COMMENT_REPLY, 'id', 'WHERE comment_id=? AND is_comment_reply_deleted=0', $comment_id);
    }
}
