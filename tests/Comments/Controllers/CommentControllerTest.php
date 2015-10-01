<?php

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
}
