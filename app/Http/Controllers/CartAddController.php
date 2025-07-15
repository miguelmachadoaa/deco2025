<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\CartRepository;
use App\Repositories\ProductRepository;


class CartAddController extends Controller
{
    public function __construct(
        protected CartRepository $cartRepository,
        protected ProductRepository $productRepository
        )
    {
    }

    public function index($id){

        $product = $this->productRepository->findArray($id);
        $cart = $this->cartRepository->agregar($product);

        return json_encode($cart);
    }

}
