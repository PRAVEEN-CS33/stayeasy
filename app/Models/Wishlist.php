<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;

    protected $table = "wishlists";

    protected $primaryKey = 'id';

    protected $fillable = [
        'accommodation_id',
        'user_id'
    ];


    public static function getData()
    {
        return self::where('user_id', auth()->user()->id)->get();
    }
    public static function store($data)
    {
        $data['user_id'] = auth()->user()->id;
        return Wishlist::create($data);
    }
    public static function remove($id)
    {
        return self::where('user_id', auth()->user()->id)
            ->where('id', $id)
            ->first()
            ->delete();
    }
}
