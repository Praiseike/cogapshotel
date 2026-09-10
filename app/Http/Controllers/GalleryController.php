<?php

namespace App\Http\Controllers;

use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $gallery = Gallery::active()->ordered()->paginate(24);

        return view('gallery.index', compact('gallery'));
    }
}
