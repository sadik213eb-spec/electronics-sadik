<?php

namespace App\Http\Controllers;

use App\Models\CustomPage;

class CustomPageController extends Controller
{
    // ===== FRONTEND ONLY =====
    public function show($slug)
    {
        $page = CustomPage::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('pages.show', compact('page'));
    }
}
