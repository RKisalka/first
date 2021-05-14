<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Student extends Model
{

    /**
     * The table used by the model
     *
     *
     * @var string
     */
    protected $table = 'student';

    /**
     * @var string[]
     */

    protected $guarded = ['id'];


}
