<?php

$this->set('version', '1.0');
$this->set('links', $this->helper('phpsoft.comments::helpers.links', $comments['data']));
$this->set('meta', function ($section) use ($comments) {

    $section->set('offset', $comments['offset']);
    $section->set('limit', $comments['limit']);
    $section->set('total', $comments['total']);
});

$this->set('entities', $this->each($comments['data'], function ($section, $comment) {

    $section->set($section->partial('phpsoft.comments::partials/comment', [ 'comment' => $comment ]));
}));

$this->set('linked', '{}');
