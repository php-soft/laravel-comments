<?php

namespace PhpSoft\Comments\Models;

use Auth;
use Illuminate\Database\Eloquent\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'comments';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['url', 'content'];

    /**
     * relation to table users
     * @return relation
     */
    public function users()
    {
        return $this->belongsToMany('App\User', 'user_id'); // @codeCoverageIgnore
    }

    /**
     * Create the model in the database.
     *
     * @param  array  $attributes
     * @return comment
     */
    public static function create(array $attributes = [])
    {
        $comment = new Comment($attributes);
        $comment->user_id = Auth::user()->id;
        $comment->save();
        return $comment;
    }

    /**
     * Update the model in the database.
     *
     * @param  array  $attributes
     * @return bool|int
     */
    public function update(array $attributes = [])
    {
        if (!parent::update($attributes)) {
            throw new Exception('Cannot update category.'); // @codeCoverageIgnore
        }

        return $this->fresh();
    }
}
