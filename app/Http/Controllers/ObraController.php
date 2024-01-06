<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\Obra;

class ObraController extends Controller
{
    /**
     * Get all obras
     *
     * @return Response
     */
    public function index(): Response
    {
        $obras = Obra::with('client')->get();
        return response($obras, 200);
    }

    /**
     * Create an obra
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request): Response
    {
        $data = $request->all();
        $image = $request->file('image');

        $obra = Obra::create($request->all());

        if ($image) {
            $directory = 'public/uploads/obras/'.$obra->id;
            $imageName = 'image.' . $image->extension();
            $imagePath = Storage::putFileAs($directory, $image, $imageName, 'public');
            $obra->image = Storage::url($imagePath);

            $absolutePathToDirectory = storage_path('app/'.$directory);
            chmod($absolutePathToDirectory, 0755);
        }

        $obra->save();
        
        return response($obra, 201);
    }

    /**
     * Get an obra by id
     *
     * @param int $id
     * @return Response
     */
    public function show(int $id): Response
    {
        $obra = Obra::with(['client', 'incomes'])->find($id);
        return response($obra, 200);
    }

    /**
     * Update an obra by id
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, int $id): Response
    {
        $obra = Obra::find($id);
        $obra->update($request->all());
        return response($obra, 200);
    }

    /**
     * Delete an obra by id
     *
     * @param int $id
     * @return Response
     */
    public function destroy(int $id): Response
    {
        $obra = Obra::find($id);
        $obra->delete();
        return response(['message' => 'Obra deleted'], 204);
    }
}
