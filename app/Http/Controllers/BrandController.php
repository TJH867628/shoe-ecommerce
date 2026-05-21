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
            'name' => ['required', 'string', 'max:255'],
        ]);

        $brand = Brand::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with(
            'success',
            'Brand created successfully.'
        );
    }

    public function updateBrand(Request $request, int $brandId)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $brand = Brand::findOrFail($brandId);

        $brand->update([
            'name' => $request->name,
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
