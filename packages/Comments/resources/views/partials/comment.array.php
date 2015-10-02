<?php

$this->extract($comment, [
    'id',
    'user_id',
    'url',
    'content',
]);

$this->set('created at', date('c', strtotime($comment->created_at)));

$this->set('updated at', date('c', strtotime($comment->updated_at)));
