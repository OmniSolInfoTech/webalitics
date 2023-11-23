<?php

namespace Osit\Webalitics\Models;
use Illuminate\Database\Eloquent\Model;

/**
 * WebaliticUser - main class
 *
 * WebaliticUser
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
class WebaliticUser extends Model
{
    protected $table = 'webalitic_user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_name',
        'website_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
    ];
}
