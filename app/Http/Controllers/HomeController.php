<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use App\Models\Service;

class HomeController extends Controller
{
    public function index()
    {
        $featuredRooms = Room::available()
            ->with('category')
            ->limit(6)
            ->get();

        $categories = Category::active()->ordered()->get();

        $services = Service::active()->limit(4)->get();

        return view('home.index', compact('featuredRooms', 'categories', 'services'));
    }
}
