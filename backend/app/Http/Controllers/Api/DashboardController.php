<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Plant;
use App\Models\Article;
use App\Models\Book;
use App\Models\Order;
use App\Models\Video;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json([
            'metrics' => [
                'total_plants' => Plant::count(),
                'total_articles' => Article::count(),
                'total_books' => Book::count(),
                'total_videos' => Video::count(),
                'total_orders' => Order::count(),
                'revenue_usd' => Order::where('payment_status', 'paid')->where('currency', 'USD')->sum('total_usd'),
                'revenue_etb' => Order::where('payment_status', 'paid')->where('currency', 'ETB')->sum('total_etb'),
            ],
            'recent_orders' => Order::latest()->take(5)->get()
        ]);
    }
}
