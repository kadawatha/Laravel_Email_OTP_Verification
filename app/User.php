<?php

namespace App;


use App\Mail\OTPMail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','isVerified'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function OTP(){

        return Cache::get($this->OTPKey());

    }

    public function OTPKey() {
        return "OTP_for_{$this->id}";
    }


    public function cacheTheOTP() {

        $OTP = rand(100000,999999);
        Cache::put([$this->OTPKey()=>$OTP],now()->addSecond(40));
        return $OTP;
    }

    public function sendOTP() {

        Mail::to('dhanushka@gmail.com')->send(new OTPMail($this->cacheTheOTP()));

    }


    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
