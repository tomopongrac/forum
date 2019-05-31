<?php

namespace App\Filters;

use App\Thread;
use Illuminate\Http\Request;

class ThreadFilters extends Filters
{
    protected $filters = ['by', 'popular', 'unanswered'];

    /**
     * Filter the query by a given username
     *
     * @param $username
     * @return mixed
     */
    protected function by($username)
    {
        $user = \App\User::where('name', $username)->firstOrFail();

        return $this->builder->where('user_id', $user->id);
    }

    /**
     * Sort the query according to most popular threads
     *
     * @return mixed
     */
    protected function popular()
    {
        $this->builder->getQuery()->orders = [];

        return $this->builder->orderBy('replies_count', 'desc');
    }

    protected function unanswered()
    {
        return $this->builder->where('replies_count', 0);
    }
}
