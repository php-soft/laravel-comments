<?php

use PhpSoft\Comments\Models\Comment;

class CommentControllerTest extends TestCase
{
    public function testCreateValidateFailure()
    {
        // test not login
        $res = $this->call('POST', '/comments');

        $this->assertEquals(401, $res->getStatuscode());

        // test validate no input
        $user = factory(App\User::class)->create();
        Auth::login($user);

        $res = $this->call('POST', '/comments');
        $results = json_decode($res->getContent());
        $this->assertEquals('error', $results->status);
        $this->assertEquals('validation', $results->type);
        $this->assertObjectHasAttribute('url', $results->errors);
        $this->assertEquals('The url field is required.', $results->errors->url[0]);
        $this->assertEquals('The content field is required.', $results->errors->content[0]);
        $this->assertEquals('The url field is required.', $results->message);

        // test validate invalid format input
        $res = $this->call('POST', '/comments', [
            'url'     => 'address comment',
            'content' => 'demo comment'
        ]);

        $results = json_decode($res->getContent());
        $this->assertEquals('error', $results->status);
        $this->assertEquals('validation', $results->type);
        $this->assertEquals('The url format is invalid.', $results->errors->url[0]);
        $this->assertEquals('The url format is invalid.', $results->message);
    }

    public function testCreateSuccess()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);

        $res = $this->call('POST', '/comments', [
            'url'     => 'http://pm.greenglobal.vn/post1',
            'content' => 'demo comment'
        ]);

        $this->assertEquals(201, $res->getStatuscode());
        $results = json_decode($res->getContent());
        $this->assertObjectHasAttribute('entities', $results);
        $this->assertInternalType('array', $results->entities);
        $this->assertEquals('http://pm.greenglobal.vn/post1', $results->entities[0]->url);
        $this->assertEquals('demo comment', $results->entities[0]->content);
        $this->assertEquals(Auth::user()->id, $results->entities[0]->user_id);
    }

    public function testUpdateNotExists()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);

        $res = $this->call('PATCH', '/comments/5');
        $this->assertEquals(404, $res->getStatusCode());
    }

    public function testUpdateValidateFailure()
    {
        // test not login
        $res = $this->call('POST', '/comments');

        $this->assertEquals(401, $res->getStatuscode());

        //check not self comment
        $user1 = factory(App\User::class)->create();
        $user2 = factory(App\User::class)->create();
        Auth::login($user1);

        $comment = factory(Comment::class)->create(['user_id' => $user2->id]);
        $res = $this->call('PATCH', '/comments/' . $comment->id, [
            'content'  => 'comment of user2',
        ]);

        $this->assertEquals(403, $res->getStatuscode());

        // test input invalid
        $comment = factory(Comment::class)->create(['user_id' => $user1->id]); 

        $res = $this->call('PATCH', '/comments/' . $comment->id, [
            'content'  => '',
        ]);

        $this->assertEquals(400, $res->getStatusCode());
        $results = json_decode($res->getContent());
        $this->assertEquals('error', $results->status);
        $this->assertEquals('validation', $results->type);
        $this->assertObjectHasAttribute('content', $results->errors);
        $this->assertEquals('The content field is required.', $results->errors->content[0]);
        $this->assertEquals('The content field is required.', $results->message);
    }

    public function testUpdateNothingChange()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);

        $comment = factory(Comment::class)->create(['user_id' => $user->id]);

        $res = $this->call('PATCH', '/comments/' . $comment->id);
        $this->assertEquals(200, $res->getStatusCode());
        $results = json_decode($res->getContent());
        $this->assertEquals($comment->content, $results->entities[0]->content);
        $this->assertEquals($comment->url, $results->entities[0]->url);
        $this->assertEquals($comment->user_id, $results->entities[0]->user_id);
    }

    public function testUpdateWithNewInformation()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);
        $comment = factory(Comment::class)->create(['user_id' => $user->id]);

        $res = $this->call('PATCH', '/comments/' . $comment->id, [
            'content'  => 'new comment',
        ]);

        $this->assertEquals(200, $res->getStatusCode());
        $results = json_decode($res->getContent());
        $this->assertEquals('new comment', $results->entities[0]->content);
        $this->assertEquals($comment->url, $results->entities[0]->url);
        $this->assertEquals($comment->user_id, $results->entities[0]->user_id);
    }

    public function testDeleteNotFound()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);

        $res = $this->call('DELETE', '/comments/1');
        $this->assertEquals(404, $res->getStatusCode());
    }

    public function testDeleteFailure()
    {
        // test not login
        $res = $this->call('DELETE', '/comments/1');

        $this->assertEquals(401, $res->getStatuscode());

        //check not self comment
        $user1 = factory(App\User::class)->create();
        $user2 = factory(App\User::class)->create();
        Auth::login($user1);

        $comment = factory(Comment::class)->create(['user_id' => $user2->id]);
        $res = $this->call('DELETE', '/comments/' . $comment->id);

        $this->assertEquals(403, $res->getStatuscode());
    }

    public function testDeleteSuccess()
    {
        $user = factory(App\User::class)->create();
        Auth::login($user);
        $comment = factory(Comment::class)->create(['user_id' => $user->id]);

        $res = $this->call('DELETE', "/comments/{$comment->id}");
        $this->assertEquals(204, $res->getStatusCode());

        $exists = Comment::find($comment->id);
        $this->assertNull($exists);
    }
}
