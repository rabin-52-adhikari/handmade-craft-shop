<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::getAllProduct();
        return view('backend.product.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $brand = Brand::get();
        $category = Category::where('is_parent', 1)->get();
        // return $category;
        return view('backend.product.create')->with('categories', $category)->with('brands', $brand);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'tags' => 'string|required',
            'description' => 'string|nullable',
            'photo.*' => 'image|required', // Validate each file as an image
            'size' => 'nullable',
            'stock' => "required|numeric",
            'cat_id' => 'required|exists:categories,id',
            'brand_id' => 'nullable|exists:brands,id',
            'is_featured' => 'sometimes|in:1',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric'
        ]);

        $data = $request->all();
        $slug = Str::slug($request->title);
        $count = Product::where('slug', $slug)->count();
        if ($count > 0) {
            $slug = $slug . '-' . date('ymdis') . '-' . rand(0, 999);
        }
        $data['slug'] = $slug;
        $data['is_featured'] = $request->input('is_featured', 0);
        $size = $request->input('size');
        if ($size) {
            $data['size'] = implode(',', $size);
        } else {
            $data['size'] = '';
        }
        $uploadedPhotos = [];
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $fileName = $slug . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/photos', $fileName);
                $uploadedPhotos[] = '/storage/photos/' . $fileName;
            }
        }

        $data['photo'] = implode(',', $uploadedPhotos);

        $status = Product::create($data);

        if ($status) {
            request()->session()->flash('success', 'Product Successfully added');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }
        return redirect()->route('product.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $brand = Brand::get();
        $product = Product::findOrFail($id);
        $category = Category::where('is_parent', 1)->get();
        $items = Product::where('id', $id)->get();
        // return $items;
        return view('backend.product.edit')->with('product', $product)
            ->with('brands', $brand)
            ->with('categories', $category)->with('items', $items);
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
        $product = Product::findOrFail($id);

        $this->validate($request, [
            'title' => 'string|required',
            'summary' => 'string|required',
            'description' => 'string|nullable',
            'photo.*' => 'image|nullable', // New images are optional
            'size' => 'nullable',
            'stock' => "required|numeric",
            'cat_id' => 'required|exists:categories,id',
            'is_featured' => 'sometimes|in:1',
            'brand_id' => 'nullable|exists:brands,id',
            'status' => 'required|in:active,inactive',
            'condition' => 'required|in:default,new,hot',
            'price' => 'required|numeric',
            'discount' => 'nullable|numeric'
        ]);

        $data = $request->all();
        $data['is_featured'] = $request->input('is_featured', 0);
        $size = $request->input('size');
        $data['size'] = $size ? implode(',', $size) : '';

        $uploadedPhotos = [];
        $existingPhotos = $request->input('existing_photos', []); // Selected existing photos

        // Handle new file uploads
        if ($request->hasFile('photo')) {
            foreach ($request->file('photo') as $file) {
                $fileName = $product->slug . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('public/photos', $fileName);
                $uploadedPhotos[] = '/storage/photos/' . $fileName;
            }
        }

        // Combine existing and new photos
        $allPhotos = array_merge($existingPhotos, $uploadedPhotos);
        $data['photo'] = implode(',', $allPhotos); // Save updated paths

        // Delete old images that are not in the updated list
        $oldPhotos = explode(',', $product->photo); // Current images in the database
        $photosToDelete = array_diff($oldPhotos, $allPhotos);

        foreach ($photosToDelete as $photo) {
            $photoPath = str_replace('storage/', 'public/', $photo);
            if (Storage::exists($photoPath)) {
                Storage::delete($photoPath); // Delete from storage
            }
        }

        // Update product
      //  dd($data);
        $status = $product->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Product Successfully updated');
        } else {
            request()->session()->flash('error', 'Please try again!!');
        }

        return redirect()->route('product.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $photos = explode(',', $product->photo);

        // Delete each image from storage
        foreach ($photos as $photo) {
            $photoPath = str_replace('storage/', 'public/', $photo); // Convert to storage path
            if (Storage::exists($photoPath)) {
                Storage::delete($photoPath); // Delete file
            }
        }

        $status = $product->delete();

        if ($status) {
            request()->session()->flash('success', 'Product successfully deleted');
        } else {
            request()->session()->flash('error', 'Error while deleting product');
        }
        return redirect()->route('product.index');
    }
}
