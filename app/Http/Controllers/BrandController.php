<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Brand;   

class BrandController extends Controller
{
    public function getAllBrands()
    {
        return Brand::all();
    }

    public function createBrand(Request $request)
    {
        $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'brand_description' => ['nullable', 'string'],
        ]);

        Brand::create([
            'brand_name' => $request->brand_name,
            'brand_description' => $request->brand_description,
        ]);

        return redirect()->back()->with(
            'success',
            'Brand created successfully.'
        );
    }

    public function updateBrand(Request $request, int $brandId)
    {
        $request->validate([
            'brand_name' => ['required', 'string', 'max:255'],
            'brand_description' => ['nullable', 'string'],
        ]);

        $brand = Brand::findOrFail($brandId);

        $brand->update([
            'brand_name' => $request->brand_name,
            'brand_description' => $request->brand_description,
        ]);

        return redirect()->back()->with(
            'success',
            'Brand updated successfully.'
        );
    }

    public function deleteBrand(int $brandId)
    {
        $brand = Brand::findOrFail($brandId);

        $brand->delete();

        return redirect()->back()->with(
            'success',
            'Brand deleted successfully.'
        );
    }
}
