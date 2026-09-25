<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannersController extends Controller
{
    public function index()
    {
        $banners = Banner::latest()->get();

        $totals = [
            'active'      => $banners->filter(fn ($b) => $b->status === 'active')->count(),
            'impressions' => $banners->sum('impressions'),
            'clicks'      => $banners->sum('clicks'),
        ];
        $totals['ctr'] = $totals['impressions'] > 0 ? round($totals['clicks'] / $totals['impressions'] * 100, 2) : null;

        return view('admin.banners.index', compact('banners', 'totals'));
    }

    public function create()
    {
        return view('admin.banners.form', ['banner' => new Banner(['slot' => Banner::SLOT_TOP, 'pages' => 'all', 'is_active' => true])]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, imageRequired: true);
        $data = array_merge($data, $this->storeImage($request));

        $banner = Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', "Banner \"{$banner->name}\" creado.");
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $data = $this->validated($request, imageRequired: false);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($banner->image_path);
            $data = array_merge($data, $this->storeImage($request));
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', "Banner \"{$banner->name}\" actualizado.");
    }

    public function toggle(Banner $banner)
    {
        $banner->update(['is_active' => ! $banner->is_active]);

        return back()->with('success', "Banner \"{$banner->name}\" " . ($banner->is_active ? 'activado' : 'pausado') . '.');
    }

    public function destroy(Banner $banner)
    {
        Storage::disk('public')->delete($banner->image_path);
        $name = $banner->name;
        $banner->delete();

        return redirect()->route('admin.banners.index')->with('success', "Banner \"{$name}\" eliminado.");
    }

    private function validated(Request $request, bool $imageRequired): array
    {
        $data = $request->validate([
            'name'       => 'required|string|max:255',
            'advertiser' => 'nullable|string|max:255',
            'slot'       => 'required|in:' . implode(',', array_keys(Banner::SLOTS)),
            'pages'      => 'required|in:' . implode(',', array_keys(Banner::PAGES)),
            'target_url' => 'required|url:http,https|max:500',
            'alt'        => 'nullable|string|max:255',
            'starts_on'  => 'nullable|date',
            'ends_on'    => 'nullable|date|after_or_equal:starts_on',
            'notes'      => 'nullable|string|max:2000',
            // Sin SVG a proposito: puede llevar scripts.
            'image'      => ($imageRequired ? 'required' : 'nullable') . '|image|mimes:jpg,jpeg,png,webp,gif|max:2048|dimensions:min_width=300,min_height=50',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['image']);

        return $data;
    }

    private function storeImage(Request $request): array
    {
        $file = $request->file('image');
        [$w, $h] = getimagesize($file->getRealPath()) ?: [null, null];

        return [
            'image_path' => $file->store('banners', 'public'),
            'width'      => $w,
            'height'     => $h,
        ];
    }
}
