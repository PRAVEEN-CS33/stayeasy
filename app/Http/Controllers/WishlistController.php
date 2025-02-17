<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorewishlistRequest;
use App\Http\Requests\UpdatewishlistRequest;
use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function index()
    {

        return Wishlist::getData();
    }
    public function createWishList(StorewishlistRequest $request)
    {
        $data = $request->validated();
        return Wishlist::store($data);
    }
    public function destroyWishList($id)
    {
        return Wishlist::remove($id);
    }
}
