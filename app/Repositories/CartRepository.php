<?php

namespace App\Repositories;

use App\Models\Productos;

final class CartRepository{

    public function carrito(){

        if (!\Session::has('cart')) {

            \Session::put('cart', array(
                'productos'=>[],
                'cantidad'=>0,
                'total'=>0
            ));
  
        }

        return \Session::get('cart');
    }

    public function agregar(array $producto)
    {

        $cart = $this->carrito();

        if(isset($cart['productos'][$producto['id']])){

            $cart['productos'][$producto['id']]['cantidad']++;

        }else{
            $cart['productos'][$producto['id']]=$producto;
            $cart['productos'][$producto['id']]['cantidad']=1;
        }


        return $this->save($cart);
    }


    public function delete($id)
    {
        $cart = $this -> carrito();

        if(isset($cart['productos'][$producto['id']])){

            unset( $cart['productos'][$producto['id']]);

        }

        return $this->save($cart);
        
    }

    public function save($cart){

        $total = 0;

        $cantidad = 0;

        foreach($cart['productos'] as $p ){

            $total = $total + $p['precio']*$p['cantidad'];

            $cantidad = $cantidad + $p['cantidad'];
        }

        $cart['total']=$total;
        
        $cart['cantidad']=$cantidad;

        \Session::put('cart', $cart);

        return $this->carrito();
    }

    public function vaciar(){
        \Session::forget('cart');
        return $this->carrito();
    }

}