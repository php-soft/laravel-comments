<?php

$this->extract($comment, [
    'id',
    'url',
    'content',
]);
$this->set('userId', $comment->user_id);

$this->set('createdAt', date('c', strtotime($comment->created_at)));

$this->set('updatedAt', date('c', strtotime($comment->updated_at)));
