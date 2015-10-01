<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;

class CommentControllerTest extends TestCase
{
    use WithoutMiddleware;

    public function testCreateValidateFailure()
    {
        $res = $this->call('POST', '/comments');
        dump(123);
    }

}
