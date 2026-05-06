<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    public function index()
    {
        $topMenus = Menu::where('location', 'top')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->get();

        $footerMenus = Menu::where('location', 'footer')
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with('children')
            ->get();

        return view('admin.menus.index', compact('topMenus', 'footerMenus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('menus', 'public');
        }

        Menu::create([
            'name' => $request->name,
            'url' => $request->url,
            'image' => $imagePath,
            'location' => $request->location ?? 'top',
            'parent_id' => $request->parent_id ?? null,
            'order' => Menu::where('location', $request->location ?? 'top')->max('order') + 1,
            'is_active' => true,
        ]);

        return response()->json(['success' => true]);
    }

    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image) {
                Storage::disk('public')->delete($menu->image);
            }
            $menu->image = $request->file('image')->store('menus', 'public');
        }

        $menu->update([
            'name' => $request->name,
            'url' => $request->url,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Menu $menu)
    {
        if ($menu->image) {
            Storage::disk('public')->delete($menu->image);
        }
        $menu->delete();

        return response()->json(['success' => true]);
    }

    public function reorder(Request $request)
    {
        foreach ($request->items as $index => $item) {
            Menu::where('id', $item['id'])->update([
                'order' => $index,
                'parent_id' => $item['parent_id'] ?? null,
                'location' => $item['location'],
            ]);
        }

        return response()->json(['success' => true]);
    }
}
