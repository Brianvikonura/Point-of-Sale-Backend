<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    // index
    public function index(Request $request)
    {
        // get all products with pagination
        $products = Product::when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->paginate(10);
        return view('pages.products.index', compact('products'));
    }

    // create
    public function create()
    {
        $categories = DB::table('categories')->get();
        return view('pages.products.create', compact('categories'));
    }

    // store
    public function store(Request $request)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'stock' => 'required|numeric',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        // store the request
        $product = new Product;
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->is_favorite = $request->is_favorite;

        $product->save();

        // save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $productId = $product->id;

            $newImageName = $productId . '_' . str_replace(' ', '_', $product->name) . '.' . $image->getClientOriginalExtension();

            $image->storeAs('public/products', $newImageName);

            $product->image = 'storage/products/' . $newImageName;
            
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    // edit
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = DB::table('categories')->get();
        return view('pages.products.edit', compact('product', 'categories'));
    }

    // update
    public function update(Request $request, $id)
    {
        // validate the request
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'category_id' => 'required',
            'stock' => 'required|numeric',
            'status' => 'required|boolean',
            'is_favorite' => 'required|boolean',
        ]);

        // update the request
        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->stock = $request->stock;
        $product->status = $request->status;
        $product->is_favorite = $request->is_favorite;
        $product->save();

        // save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $productId = $product->id;

            $newImageName = $productId . '_' . str_replace(' ', '_', $product->name) . '.' . $image->getClientOriginalExtension();

            if ($product->image) {
                $oldImagePath = public_path($product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image->storeAs('public/products', $newImageName);

            $product->image = 'storage/products/' . $newImageName;
            $product->save();
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully');
    }

    // destroy
    public function destroy($id)
    {
        $product = Product::find($id);

        if ($product->image) {

            $imagePath = public_path($product->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }
}
