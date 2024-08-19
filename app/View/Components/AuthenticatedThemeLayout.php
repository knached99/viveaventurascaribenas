<?php 

namespace App\View\Components;

use Illuminate\View\Component;

class AuthenticatedThemeLayout extends Component
{
    public function render()
    {
        return view('layouts.authenticated-theme');
    }
}
