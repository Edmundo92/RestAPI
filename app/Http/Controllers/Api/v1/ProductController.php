<?php

namespace App\Http\Controllers\Api\v1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductController extends Controller
{
    private $product;
    private $totalPaginate = 2;

    public function __construct(Product $product) {
        $this->product = $product; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = $this->product->all();
        //$products = $this->product->paginate($this->totalPaginate);
        return response()->json(['data' => $products]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = validator($data, $this->product->rules());
        if($validate->fails()){
            $message = $validate->messages();
            return response()->json(['validate.error', $message]);
        }

        $insert = $this->product->create($data);

        if(!$insert) {
            return response()->json(['error' => 'Erro ao inserir']);
        }

        return response()->json($insert);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->find($id);
        if(!$product){
            return response()->json(['error' => 'NOTFOUND']);
        }
        return response()->json(['data' => $product]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validate = validator($data, $this->product->rules($id));
        if($validate->fails()){
            $message = $validate->messages();
            return response()->json(['validate.error', $message]);
        }

        $product = $this->product->find($id);
        if(!$product){
            return response()->json(['error' => 'NOTFOUND']);
        }

        $update = $product->update($data);
        if(!$update){
            return response()->json(['error' => 'product not update'], 500);
        }

        return response()->json(['data' => $update]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = $this->product->find($id);
        if(!$product){
            return response()->json(['error' => 'NOTFOUND']);
        }

        $delete = $product->delete();
        if(!$delete){
            return response()->json(['error' => 'product not delete'], 500);
        }
        return response()->json(['response' => $delete]);
    }

    public function search(Request $request){
        $data = $request->all();
        $validate = validator($data, $this->product->rulesSearch());
        if($validate->fails()){
            $message = $validate->messages();
            return response()->json(['validate.error', $message]);
        }

        $products = $this->product->search($data);

        return response()->json(['data' => $products]);

    }

    // public function validateMethod($data){
        
    // }
}
