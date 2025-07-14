<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\BannerImage;
use Illuminate\Support\Facades\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $banners = Banner::paginate(5);

        return view('admin.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $banners = Banner::all();
        $bannerTypes = $this->bannerTypes();
        return view('admin.banners.create',compact('banners', 'bannerTypes'));
    }
    protected function bannerTypes()
    {

      return collect(Config::get('bannerTypes'))->sortBy(function ($value, $key) {
        return $value['id'];
      });
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {


        $data = $request->except('image');

        // Create the banner
        $banner = Banner::create($data);

        // Check if the request has a file and process the image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName(); // Ensure unique filename
            $path = $image->storeAs('banners', $imageName, 'public');

            $bannerImage = new BannerImage();
            $bannerImage->image_name = $path;
            $bannerImage->banner_id = $banner->id;
            $bannerImage->save();
        }

        return redirect()->route('banners.index', [app()->getLocale()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

        $bannerTypes = $this->bannerTypes();
        $banner = Banner::findOrFail($id);
        return view('admin.banners.edit' , compact('banner','bannerTypes'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,webp|max:2048',
            // Add other validation rules as needed
        ]);

        $banner = Banner::findOrFail($id);
        $banner->update($request->except('images'));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName(); // Ensure unique filename
                $path = $image->storeAs('banners', $imageName, 'public');

                // Create and save banner image instance
                $bannerImage = new BannerImage();
                $bannerImage->image_name = $path; // Store the file path instead of the file name
                $bannerImage->banner_id = $banner->id;
                $bannerImage->save();
            }
        }

        return redirect()->route('banners.index', [app()->getLocale()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $banner = Banner::findOrFail($id);
        $banner->delete();

        return redirect()->route('banners.index', app()->getLocale());
    }
    public function deleteImage(Request $request, $image_id)
    {
        // Find the image by its ID
        $image = BannerImage::findOrFail($image_id);

        // Delete the image from storage
        Storage::delete('banners/'.$image->image_name);

        // Delete the image record from the database
        $image->delete();

        // Return a JSON response with a success message
        return response()->json(['success' => 'Files Deleted']);
    }
}
