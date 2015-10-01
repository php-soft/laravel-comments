<?php

namespace PhpSoft\Comments\Controllers;

use Input;
use Illuminate\Http\Request;
use Validator;

use App\Http\Requests;
use App\User;
use PhpSoft\Comments\Models\Comment;
use PhpSoft\Comments\Controllers\Controller;

class CommentController extends Controller
{
    public function store()
    {
        dump('abc');
    }

}
