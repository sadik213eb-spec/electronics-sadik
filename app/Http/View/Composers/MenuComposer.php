<?php

namespace App\Http\View\Composers;

use App\Models\Menu;
use Illuminate\View\View;

class MenuComposer
{
    public function compose(View $view): void
    {
        $topMenus = Menu::where('location', 'top')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->get();

        $footerMenus = Menu::where('location', 'footer')
            ->where('is_active', true)
            ->whereNull('parent_id')
            ->orderBy('order')
            ->with(['children' => function ($query) {
                $query->where('is_active', true)->orderBy('order');
            }])
            ->get();

        $view->with('topMenus', $topMenus);
        $view->with('footerMenus', $footerMenus);
    }
}
