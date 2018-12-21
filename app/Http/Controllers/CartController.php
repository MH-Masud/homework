<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Cart;

class CartController extends Controller
{
    function index()
    {
    	$products = DB::table('products')->get();

    	return view('welcome',compact('products'));
    }

    function product(Request $request)
    {
    	$id = $request->input('id');
    	$product = DB::table('products')
    	              ->where('id',$id)
    	              ->first();
    	$output = array(
            
            'id'=>$product->id,
            'name'=>$product->name,
            'price'=>$product->price,
            'unit'=>$product->unit,
            'color'=>$product->color
    	);
    	echo json_encode($output);
    }

    function cart(Request $request)
    {
    	$products = $request->input('table_data');
    	for ($i=0; $i < count($products); $i++) { 
    		$data = array();
    		$data['id'] = $products[$i]['id'];
    		$data['name'] = $products[$i]['name'];
    		$data['price'] = $products[$i]['price'];
    		$data['quantity'] = 1;
    		$data['attributes']['color'] = $products[$i]['color'];
    		$data['attributes']['unit'] = $products[$i]['unit'];
    		Cart::add($data);
    	}
    }
    function get(Request $request)
    {
    	$output = '';
    	$cart_contents = Cart::getContent();
    	$qtn = Cart::getTotalQuantity();
    	$subTotal = Cart::getSubTotal();
    	$output .='
    	     <br>
    	     <h3 class="text-success text-center">Shopping Buscate</h3>
             <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Color</th>
                        <th>Unit</th>
                        <th>SubTotal</th>
                    </tr>
                </thead>
    	';
    	foreach ($cart_contents as $cart_content) {
    		
    		$output  .='

                <tbody>
                    <tr>
                        <td>'.$cart_content->name.'</td>
                        <td>'.$cart_content->price.'</td>
                        <td>'.$cart_content->quantity.'</td>
                        <td>'.$cart_content->attributes->color.'</td>
                        <td>'.$cart_content->attributes->unit.'</td>
                        <td>'.$cart_content->price*$cart_content->quantity.'</td>
                    </tr>
                </tbody>
    		';
    	}
    	$output.='
                <tfoot>
                    <tr>
                        <th>Total</th>
                        <th></th>
                        <th>'.$qtn.'</th>
                        <th></th>
                        <th></th>
                        <th>'.$subTotal.'</th>
                    </tr>
                </tfoot>
    	';
    	$output .='</table>';
    	echo $output;
    }

    function save(Request $request)
    {
    	$cart_contents = Cart::getContent();

    	foreach ($cart_contents as $cart_content) {
    		$sells = array();
            $sells['name'] = $cart_content->name;
            $sells['price'] = $cart_content->price;
            $sells['quantity'] = $cart_content->quantity;
            $sells['color'] = $cart_content->attributes->color;
            $sells['unit'] = $cart_content->attributes->unit;
            $sells['subtotal'] = $cart_content->quantity*$cart_content->price;
            DB::table('sells')->insert($sells);
    	}
    	
    	Cart::clear();
    }
}
