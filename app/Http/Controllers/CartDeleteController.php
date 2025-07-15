<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CartRepository;

class CartDeleteController extends Controller
{
    public function __construct(
        protected CartRepository $cartRepository,
        )
    {
    }

    public function index($id){

        $cart = $this->cartRepository->delete($id);

        return json_encode($cart);
    }

}
