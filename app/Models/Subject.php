<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{

    /**
     * The table used by the model
     *
     *
     * @var string
     */
    protected $table = 'subject';

    /**
     * @var string[]
     */

    protected $guarded = ['id'];

}
