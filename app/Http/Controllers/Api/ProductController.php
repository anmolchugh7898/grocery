<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Products;
use App\Models\ProductImages;

class ProductController extends Controller
{
    public function addProducts(Request $request) 
    {
        $data = [];
        $data['title'] = $request->title;
        $data['description'] = $request->description;
        $data['category_id'] = $request->category_id;
        $data['rating'] = $request->rating;
        $data['price'] = $request->price;

        $query = Products::create($data);
        return [
            'status' => 200,
            'success' => true,
            'message' => 'Product added successfully!',
            'data' => []
        ];
    }

    public function addProductImages(Request $request)
    {
        $query = Products::where('id', $request->product_id)->first();
        $category = Category::where('id', $query->category_id)->first('name');
        $images = $request->image;
        $dataPacket = [];
        foreach($images as $i => $row) {
            if($request->hasFile('image')) {
                $dataPacket['product_id'] = $query->id;
                $dataPacket['image'] = time() .'-'. $i . '.' . $row->getClientOriginalExtension();
                $row->move(public_path('product_images/'.$category->name."/"), $dataPacket['image']);
                ProductImages::create($dataPacket);
            }else{
                return response()->json('image null');
            }
        }
        return [
            'status' => 200,
            'success' => true,
            'message' => 'Product images added successfully!',
            'data' => []
        ];
    }

    public function addCategory(Request $request) 
    {
        $data['name'] = $request->name;
        $query = Category::create($data);
        return [
            'status' => 200,
            'success' => true,
            'message' => 'Category added successfully!',
            'data' => []
        ];
    }

    public function getCategoryList() 
    {
        $data = Category::get();
        $dataPacket = [];

        foreach($data as $i => $row) {
            $dataPacket[$i]['id'] = $row['id'];
            $dataPacket[$i]['name'] = $row['name'];
        }
        return [
            'status' => 200,
            'success' => true,
            'message' => 'Category List',
            'data' => $dataPacket
        ];
    }

    public function getProductList(Request $request) 
    {
        $data = Products::with('productImages')->where('title', 'like', '%' . $request->search . '%')->get();
        $dataPacket = [];

        foreach($data as $i => $row) {
            $dataPacket[$i]['title'] = $row['title'];
            $dataPacket[$i]['description'] = $row['description'];
            $category = $this->getCategoryById($row['category_id']);
            $dataPacket[$i]['category'] = $category;
            $category_name = trim($category);
            $dataPacket[$i]['rating'] = $row['rating'];

            $images = $row['productImages'];
            foreach($images as $key => $image) {
                $dataPacket[$i]['images'][$key] = 'product_images/'.$category_name."/".$image['image'];
            }
        }
        return [
            'status' => 200,
            'success' => true,
            'message' => 'Product List',
            'data' => $dataPacket
        ];

    }

    public function getCategoryById($id) 
    {
        $data = Category::where('id', $id)->first();
        return $data['name'];
    }

    public function getProductbyCategory(Request $request) 
    {
        $data = Products::with('productImages')->where('category_id', $request->category_id)->get();
        $dataPacket = [];

        foreach($data as $i => $row) {
            $dataPacket[$i]['title'] = $row['title'];
            $dataPacket[$i]['description'] = $row['description'];
            $category = $this->getCategoryById($row['category_id']);
            $dataPacket[$i]['category'] = $category;
            $category_name = trim($category);
            $dataPacket[$i]['rating'] = $row['rating'];

            $images = $row['productImages'];
            foreach($images as $key => $image) {
                $dataPacket[$i]['images'][$key] = 'product_images/'.$category_name."/".$image['image'];
            }
        }
        if(count($data) > 0) {
            return [
                'status' => 200,
                'success' => true,
                'message' => 'Product List',
                'data' => $dataPacket
            ]; 
        } else {
            return [
                'status' => 201,
                'success' => false,
                'message' => 'No products for this category',
                'data' => []
            ]; 
        }
         
    }

}
