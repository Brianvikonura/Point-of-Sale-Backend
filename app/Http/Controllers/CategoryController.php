<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    //index
    public function index(Request $request)
    {
        // get all categories with pagination
        $categories = Category::when($request->input('name'), function ($query, $name) {
            return $query->where('name', 'like', '%' . $name . '%');
        })->paginate(10);
        return view('pages.categories.index', compact('categories'));
    }

    //create
    public function create()
    {
        return view('pages.categories.create');
    }

    //store
    public function store(Request $request)
    {
        //validate the request
        $request->validate([
            'name' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //store the request
        $category = new Category;
        $category->name = $request->name;
        $category->description = $request->description;

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $categoryId = $category->id;

            $newImageName = $categoryId . '_' . str_replace(' ', '_', $category->name) . '.' . $image->getClientOriginalExtension();

            $image->storeAs('public/categories', $newImageName);

            $category->image = 'storage/categories/' . $newImageName;

            $category->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category created successfully');
    }

    //show
    public function show($id)
    {
        return view('pages.categories.show');
    }

    //edit
    public function edit($id)
    {
        $category = Category::find($id);
        return view('pages.categories.edit', compact('category'));
    }

    //update
    public function update(Request $request, $id)
    {
        //validate the request...
        $request->validate([
            'name' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg',
        ]);

        //update the request...
        $category = Category::find($id);
        $category->name = $request->name;
        $category->description = $request->description;

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');

            $categoryId = $category->id;

            $newImageName = $categoryId . '_' . str_replace(' ', '_', $category->name) . '.' . $image->getClientOriginalExtension();
            if ($category->image) {
                $oldImagePath = public_path($category->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $image->storeAs('public/categories', $newImageName);

            $category->image = 'storage/categories/' . $newImageName;
        }

        $category->save();

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    //destroy
    public function destroy($id)
    {
        $category = Category::find($id);

        if ($category->image) {

            $imagePath = public_path($category->image);

            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully');
    }
}
