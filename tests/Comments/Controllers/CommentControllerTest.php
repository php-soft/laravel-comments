<?php

class CommentControllerTest extends TestCase
{
    public function testCreateValidateFailure()
    {

        $user = factory(App\User::class)->create();
        $res = $this->call('POST', '/comments');
        dump($res->getStatuscode());
    }

}
