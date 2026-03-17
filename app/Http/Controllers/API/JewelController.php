<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Jewel;
use App\Enums\Status;
use Illuminate\Http\Request;

class JewelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $jewels = Jewel::where('online', Status::ONLINE)->get();
        foreach ($jewels as $jewel) {
            $jewel->getFirstMedia("jewels/images");
        }
        return response()->json($jewels);
    }

    /**
     * Get 8 random jewels.
     */
    public function random()
    {
        $jewels = Jewel::where('online', Status::ONLINE)->inRandomOrder()->take(8)->get();
        foreach ($jewels as $jewel) {
            $jewel->getFirstMedia("jewels/images");
        }
        return response()->json($jewels);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Jewel $jewel)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jewel $jewel)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jewel $jewel)
    {
        //
    }
}
