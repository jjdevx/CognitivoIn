<?php

namespace App;

use App\Profile;
use Illuminate\Database\Eloquent\Model;

class ItemProperty extends Model
{
    //

    public function profile()
    {
        return $this->belongsTo(Item::class);
    }
}
