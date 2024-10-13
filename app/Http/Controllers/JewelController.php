<?php

namespace App\Http\Controllers;

use App\Models\Jewel;
use App\Models\Collection;
use App\Enums\Type;
use App\Enums\Material;
use Illuminate\Http\Request;

class JewelController extends Controller
{
    public function index(Request $request)
    {
        $query = Jewel::query();

        // Apply filters
        if ($request->has('type')) {
            $query->whereJsonContains('type', $request->type);
        }

        if ($request->has('material')) {
            $query->whereJsonContains('material', $request->material);
        }

        if ($request->has('collection')) {
            $query->where('collection_id', $request->collection);
        }

        $jewels = $query->paginate(12);

        $types = Type::cases();
        $materials = Material::cases();
        $collections = Collection::all();

        return view('pages.jewels.index', compact('jewels', 'types', 'materials', 'collections'));
    }
}