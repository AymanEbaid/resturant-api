<?php

namespace App\Http\Controllers;

use App\Models\MenuItem;
use App\Trait\apiresponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuItemController extends Controller
{
    use apiresponse;

    /**
     * عرض كل عناصر المنيو
     */
    public function index()
    {
        $menuItems = MenuItem::with('category')->get();
        return $this->success($menuItems, 'Menu items retrieved successfully.');
    }

    /**
     * إضافة عنصر جديد
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'required|exists:categories,id',
        ]);

        // رفع الصورة
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }

        $menuItem = MenuItem::create($data);

        return $this->success($menuItem, 'Menu item created successfully.', 201);
    }

    /**
     * عرض عنصر محدد
     */
    public function show($id)
    {
        $menuItem = MenuItem::with('category')->find($id);

        if (!$menuItem) {
            return $this->error('Menu item not found', 404);
        }

        return $this->success($menuItem, 'Menu item retrieved successfully.');
    }

    /**
     * تعديل عنصر
     */
    public function update(Request $request, MenuItem $menuItem)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'category_id' => 'sometimes|exists:categories,id',
        ]);

        // لو فيه صورة جديدة
        if ($request->hasFile('image')) {
            if ($menuItem->image) {
                Storage::disk('public')->delete($menuItem->image);
            }
            $data['image'] = $request->file('image')->store('menu_images', 'public');
        }

        $menuItem->update($data);

        return $this->success($menuItem, 'Menu item updated successfully.');
    }

    /**
     * حذف عنصر
     */
    public function destroy(MenuItem $menuItem)
    {
        if ($menuItem->image) {
            Storage::disk('public')->delete($menuItem->image);
        }

        $menuItem->delete();

        return $this->success(null, 'Menu item deleted successfully.');
    }

    /**
     * عرض المنيو حسب الفئة
     */
    public function getByCategory($categoryId)
    {
        $menuItems = MenuItem::with('category')
            ->where('category_id', $categoryId)
            ->get();

        if ($menuItems->isEmpty()) {
            return $this->success([], 'No menu items found for this category');
        }

        return $this->success($menuItems, 'Menu items retrieved successfully.');
    }
}
