<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Category;
use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories =Category::all();

        $products = Product::when($request->search, function($q) use($request){

            return $q->whereTranslationLike('name', '%' . $request->search . '%');

        })->when($request->category_id, function($q) use($request){
            return $q->where('category_id', $request->category_id);
        })->latest()->paginate(5);

        return view('dashboard.products.index',compact('categories','products'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories=Category::all();
        return view('dashboard.products.create',compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules=[
            'category_id' => 'required',
        ];

        foreach(config('translatable.locales') as $locale){

            $rules += [$locale . '.name' => ['required',Rule::unique('product_translations','name')]];
            $rules += [$locale . '.description' => 'required'];
        }
        $rules += [
            'purchase_price' =>'required',
            'sale_price' =>'required',
            'stock' =>'required',
        ];

        $request->validate($rules);

        $request_data=$request->all();

        if ($request->image) {
            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/product_images/' . $request->image->hashName()));
            $request_data['image']=$request->image->hashName();
        }

        Product::create($request_data);
        session()->flash('success',__('site.added_successfuly'));
        return redirect()->route('dashboard.products.index');

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('dashboard.products.edit',compact('categories','product'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $rules=[
            'category_id' => 'required',
        ];

        foreach(config('translatable.locales') as $locale){

            $rules += [$locale . '.name' => ['required',Rule::unique('product_translations','name')->ignore($product->id,'product_id')]];
            $rules += [$locale . '.description' => 'required'];
        }
        $rules += [
            'purchase_price' =>'required',
            'sale_price' =>'required',
            'stock' =>'required',
        ];

        $request->validate($rules);

        $request_data=$request->all();

        if ($request->image) {

            if ($product->image != 'default.png') {
                
                Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

            } // end of inner if

            Image::make($request->image)
                ->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })
            ->save(public_path('uploads/product_images/' . $request->image->hashName()));
            $request_data['image']=$request->image->hashName();
        }

        $product->update($request_data);
        session()->flash('success',__('site.updated_successfuly'));
        return redirect()->route('dashboard.products.index');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        if ($product->image != 'default.png') {
                
            Storage::disk('public_uploads')->delete('/product_images/' . $product->image);

        } // end of inner if
        $product->delete();
        \session()->flash('success',__('site.deleted_successfuly'));
        return \redirect()->route('dashboard.products.index');
    }
}
