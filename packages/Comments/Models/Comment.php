<?php

namespace PhpSoft\Comments\Models;

use Auth;
use Illuminate\Database\Eloquent\Exception;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
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
        return $this->belongsTo('App\User', 'user_id'); // @codeCoverageIgnore
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

    /**
     * Browse items
     * 
     * @param  array  $options
     * @return array
     */
    public static function browse($options = [])
    {
        $find = new Comment();
        $find = $find->where('url', $options['url'])->orderBy('id', 'DESC');

        $total = $find->count();

        if (!empty($options['offset'])) {
            $find = $find->skip($options['offset']);
        }

        if (!empty($options['limit'])) {
            $find = $find->take($options['limit']);
        }

        if (!empty($options['cursor'])) {
            $find = $find->where('id', '<', $options['cursor']);
        }

        return [
            'total'  => $total,
            'offset' => empty($options['offset']) ? 0 : $options['offset'],
            'limit'  => empty($options['limit']) ? 0 : $options['limit'],
            'data'   => $find->get(),
        ];
    }
}
