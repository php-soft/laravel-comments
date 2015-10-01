<?php

$this->set('version', '1.0');
$this->set('links', '{}');
$this->set('meta', '{}');

$this->set('entities', $this->each([ $comment ], function ($section, $comment) {

    $section->set($section->partial('phpsoft.comments::partials/comment', [ 'comment' => $comment ]));
}));

$this->set('linked', '{}');
