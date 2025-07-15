<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;


class CartEmptyController extends Controller
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected ProductRepository $productRepository
        )
    {
    }

    public function index(){

        $cart = $this->cartRepository->vaciar();

        return json_encode($cart);
    }

}
