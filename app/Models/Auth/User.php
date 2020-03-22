<?php

namespace App\Models\Auth;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use App\Models\Auth\SocialUser;
use App\Models\Product\ProductSpu;
use App\Models\Product\CartItem;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'phone', 'password',  'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'openid', 'unionid'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * JWTSubject 对象的接口用于获取当前用户的 id
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * JWTSubject 对象的接口用于额外在 JWT 载荷中增加的自定义内容
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getAvatarAttribute($value)
    {
        return !empty($value) ? config('app.url') . $value : '';
    }

    /**
     * 一个用户允许多种第三方授权登录
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialUsers()
    {
        return $this->hasMany(SocialUser::class, 'user_id', 'id');
    }

    /**
     * 一个用户允许有多个收货地址
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function addresses()
    {
        return $this->hasMany(UserAddress::class, 'user_id', 'id');
    }

    /**
     * 用户收藏商品 -（用户-商品多对多关联）
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function favoriteProducts()
    {
        return $this->belongsToMany(ProductSpu::class, 'user_favorite_products', 'user_id', 'product_id')
            ->withTimestamps()
            ->orderBy('user_favorite_products.created_at', 'desc');
    }

    /**
     * 用户-购物车记录 - （一对多关联）
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id', 'id');
    }

}
