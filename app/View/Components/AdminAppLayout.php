<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class AdminAppLayout extends Component
{
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View
    {
        // Ini akan memuat tampilan blade dari resources/views/layouts/admin-app.blade.php
        return view('layouts.admin-app');
    }
}