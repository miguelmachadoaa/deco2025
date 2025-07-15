<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CartRepository;

class CartDetailController extends Controller
{

    public function __construct(protected CartRepository $cartRepository)
    {
    }

    public function index(){

        $cart = $this->cartRepository->carrito();

        return view('cart.show', compact('cart'));
    }
}
