<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Overtrue\LaravelFollow\Traits\CanBeLiked;
use App\Profile;
use App\ItemImage;
use App\ItemTag;
use App\ItemReview;
use App\ItemProperty;
use App\ItemFaq;

use Laravel\Scout\Searchable;

use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;

class Item extends Model implements HasMedia
{
    use SoftDeletes, CanBeLiked;
    use HasMediaTrait;
    use Searchable;
    use \Spatie\Tags\HasTags;

    protected $table = 'items';

    /**
    * Fields that can be mass assigned.
    *
    * @var array
    */
    protected $fillable = [
        'name',
        'sku',
        'short_description',
        'long_description',
        'currency_id',
        'unit_price',
        'unit_cost',
        'is_active'
    ];

    public function toSearchableArray()
    {
        return $this->only('name', 'sku', 'short_description');
    }

    public function scopeGetItems($query, $id)
    {
        return $query->where('profile_id', $id);
    }

    public function creator()
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }
    public function cart()
    {
        return $this->belongsTo(Cart::class,'id');
    }

    public function faqs()
    {
        return $this->hasMany(ItemFaq::class);
    }

    public function images()
    {
        return $this->hasMany(ItemImage::class);
    }

    public function properties()
    {
        return $this->hasMany(ItemProperty::class);
    }

    public function reviews()
    {
        return $this->hasMany(ItemReview::class);
    }

    public function tags()
    {
        return $this->hasMany(ItemTag::class);
    }

    public function targets()
    {
        return $this->morphToMany('App\Item', 'targetable');
    }

    public $registerMediaConversionsUsingModelInstance = true;

    public function registerMediaConversions(Media $media = null)
    {
        $this->addMediaConversion('thumb')
        ->width(200)
        ->height(200)
        ->performOnCollections('images', 'downloads');
    }
}
