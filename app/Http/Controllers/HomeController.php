<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\DropPoint;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::active();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        $packages = $query->orderBy('created_at', 'desc')->paginate(9);
        $categories = Package::active()->distinct()->pluck('category')->filter()->sort()->values();
        $dropPoints = DropPoint::active()->orderBy('region')->get();
        $userActiveOrdersCount = auth()->check()
            ? auth()->user()->orders()->whereNotIn('status', ['selesai', 'dibatalkan'])->count()
            : 0;

        return view('home', compact('packages', 'categories', 'dropPoints', 'search', 'category', 'userActiveOrdersCount'));
    }

    public function show(Package $package)
    {
        if (!$package->is_active) {
            abort(404);
        }
        return view('packages.show', compact('package'));
    }
}
