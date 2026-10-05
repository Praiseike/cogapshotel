<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $rooms = Room::available()->with('category')->get();
        $categories = Category::active()->get();

        $urls = [
            ['loc' => route('home'), 'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => route('rooms.index'), 'priority' => '0.9', 'changefreq' => 'daily'],
            ['loc' => route('services.index'), 'priority' => '0.7', 'changefreq' => 'weekly'],
            ['loc' => route('gallery.index'), 'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => route('contact.show'), 'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => route('about'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('policies'), 'priority' => '0.5', 'changefreq' => 'monthly'],
            ['loc' => route('terms'), 'priority' => '0.4', 'changefreq' => 'yearly'],
            ['loc' => route('privacy'), 'priority' => '0.4', 'changefreq' => 'yearly'],
        ];

        foreach ($rooms as $room) {
            $urls[] = [
                'loc' => route('rooms.show', $room->slug),
                'priority' => '0.8',
                'changefreq' => 'weekly',
                'lastmod' => $room->updated_at->toAtomString(),
            ];
        }

        $xml = view('sitemap', compact('urls'))->render();

        return response($xml, 200)->header('Content-Type', 'text/xml');
    }

    public function robots(): Response
    {
        $content = "User-agent: *\n";
        $content .= "Allow: /\n";
        $content .= "Disallow: /admin/\n";
        $content .= "Disallow: /dashboard/\n";
        $content .= "Sitemap: ".route('sitemap')."\n";

        return response($content, 200)->header('Content-Type', 'text/plain');
    }
}
