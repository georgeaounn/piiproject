<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    protected $fillable = [
        'email',
        'password',
        'table_connection'
    ];



    protected static function boot()
    {
        parent::boot();

        //-- creating
        static::retrieved(function ($model) {

            $database_connection = config('database.default');
            config(['database.default' => $model->table_connection]);

            $person = Person::where('email', $model->email)->first();
            $model->first_name = $person->first_name;
            $model->last_name = $person->last_name;

            config(['database.default' => $database_connection]);

        });
    }
}
