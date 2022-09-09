<?php
class CommentModel extends Model
{
    function __construct()
    {
        parent::__construct();
    }
    function GetCommentsCount(string $condition, $value)
    {
        return $this->database->Get(TABLE_COMMENT, 'COUNT(id)', $condition, $value, 'SINGULAR');
    }
    function GetComments(string $columns, string $condition, string $item_id)
    {
        return $this->database->Get(TABLE_COMMENT, $columns, $condition, $item_id, 'PLURAL');
    }
    function GetCommentsReply(string $columns, string $condition, $comment_id)
    {
        return $this->database->Get(TABLE_COMMENT_REPLY, $columns, $condition, $comment_id, 'PLURAL');
    }

    


    
    function GetCommentById(string $comment_id)
    {
        return $this->database->Get(TABLE_COMMENT, 'id,user_id,date_comment_created', 'WHERE id=? AND is_comment_deleted=0', $comment_id, 'SINGULAR');
    }
    function GetComment(array $inputs)
    {
        return $this->database->Get(TABLE_COMMENT, 'id', 'WHERE id=? AND user_id=? AND is_comment_deleted=0', $inputs, 'SINGULAR');
    }
    function CreateComment(array $inputs)
    {
        return $this->database->Create(TABLE_COMMENT, $inputs);
    }
    function UpdateComment(array $inputs)
    {
        return $this->database->Update(TABLE_COMMENT, $inputs);
    } 
    function GetCommentReplyById(string $comment_reply_id)
    {
        return $this->database->Get(TABLE_COMMENT_REPLY, 'id,comment_id,user_id,date_comment_reply_created', 'WHERE id=? AND is_comment_reply_deleted=0', $comment_reply_id, 'SINGULAR');
    }
    function GetCommentReply(array $inputs)
    {
        return $this->database->Get(TABLE_COMMENT_REPLY, 'id,comment_id', 'WHERE id=? AND comment_id=? AND user_id=? AND is_comment_reply_deleted=0', $inputs, 'SINGULAR');
    } 
    function CreateCommentReply(array $inputs)
    {
        return $this->database->Create(TABLE_COMMENT_REPLY, $inputs);
    }
    function UpdateCommentReply(array $inputs)
    {
        return $this->database->Update(TABLE_COMMENT_REPLY, $inputs);
    }
    function CommentHasReply(string $comment_id)
    {
        return $this->database->Get(TABLE_COMMENT_REPLY, 'id', 'WHERE comment_id=? AND is_comment_reply_deleted=0', $comment_id, 'PLURAL');
    }
}
