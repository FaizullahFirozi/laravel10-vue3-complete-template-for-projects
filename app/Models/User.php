<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Http\Request;


// ADD FOR VUE SECTION PERMISSION
use LaravelAndVueJS\Traits\LaravelPermissionToVueJS;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, LogsActivity, LaravelPermissionToVueJS;
   
  
     // this method for LogsActivity only; **********
     public function getActivitylogOptions(): LogOptions
     {
        
         return LogOptions::defaults()
             ->useLogName('کارمندان')
             ->logOnly([ 'name',
             'last_name',
             'father_name',
             'dob',
             'nic',
             'hire_date',
             'gross_salary',
             'phone',
             'photo',
             'account_status',
             'email',
             'password',])->logOnlyDirty(); // Replace with the desired attributes to be logged
     }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'last_name',
        'father_name',
        'dob',
        'nic',
        'hire_date',
        'gross_salary',
        'phone',
        'avatar',
        'account_status',
        'email',
        'password',
    ]; 

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    // public function Activity()
    // {
    //     return $this->belongsToMany(Activity::class);
    // }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    // FRZ FOR ADDING THE PHOTO LINK ADDRESS
    public function avatar(): Attribute {
         return Attribute::make(
            get: fn ($value) => asset(Storage::url($value) ?? 'logo.png'),
         );
    }
  
}
