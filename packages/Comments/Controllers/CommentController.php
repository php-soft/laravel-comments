<?php

namespace PhpSoft\Comments\Controllers;

use Auth;
use Input;
use Validator;

use App\Http\Requests;
use App\User;
use Illuminate\Http\Request;
use PhpSoft\Comments\Models\Comment;
use PhpSoft\Comments\Controllers\Controller;

class CommentController extends Controller
{
    /**
     * Create resource action
     * 
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'url'     => 'required|string|url',
            'content' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(arrayView('phpsoft.comments::errors/validation', [
                'errors' => $validator->errors()
            ]), 400);
        }

        $comment = Comment::create($request->all());

        return response()->json(arrayView('phpsoft.comments::comment/read', [
            'comment' => $comment
        ]), 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int     $id
     * @param  Request $request
     * @return Response
     */
    public function update($id, Request $request)
    {
        $comment = Comment::find($id);

        // check exists
        if (empty($comment)) {
            return response()->json(null, 404);
        }

        // check self comment
        if ($comment->user_id != Auth::user()->id) {
            return response()->json(null, 403);
        }

        // check validate
        $validator = Validator::make($request->all(), [
            'content' => 'sometimes|required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(arrayView('phpsoft.comments::errors/validation', [
                'errors' => $validator->errors()
            ]), 400);
        }

        $comment = $comment->update($request->all());

        return response()->json(arrayView('phpsoft.comments::comment/read', [
            'comment' => $comment
        ]), 200);
    }
}
