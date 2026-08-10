<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DropPoint;
use Illuminate\Http\Request;

class DropPointController extends Controller
{
    public function index(Request $request)
    {
        $query = DropPoint::query();

        if ($search = $request->get('search')) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->get('status') !== '') {
            $query->where('is_active', $request->get('status'));
        }

        $dropPoints = $query->orderBy('region')->paginate(15);
        return view('admin.drop-points.index', compact('dropPoints'));
    }

    public function create()
    {
        return view('admin.drop-points.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'address'            => ['required', 'string'],
            'region'             => ['required', 'string', 'max:100'],
            'contact_number'     => ['nullable', 'string', 'max:20'],
            'operational_hours'  => ['nullable', 'string', 'max:100'],
            'is_active'          => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active', true);
        DropPoint::create($data);

        return redirect()->route('admin.drop-points.index')
            ->with('success', 'Drop point berhasil ditambahkan.');
    }

    public function edit(DropPoint $dropPoint)
    {
        return view('admin.drop-points.edit', compact('dropPoint'));
    }

    public function update(Request $request, DropPoint $dropPoint)
    {
        $data = $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'address'            => ['required', 'string'],
            'region'             => ['required', 'string', 'max:100'],
            'contact_number'     => ['nullable', 'string', 'max:20'],
            'operational_hours'  => ['nullable', 'string', 'max:100'],
            'is_active'          => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $dropPoint->update($data);

        return redirect()->route('admin.drop-points.index')
            ->with('success', 'Drop point berhasil diperbarui.');
    }

    public function toggle(DropPoint $dropPoint)
    {
        $dropPoint->update(['is_active' => !$dropPoint->is_active]);
        $status = $dropPoint->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return back()->with('success', "Drop point berhasil {$status}.");
    }
}
