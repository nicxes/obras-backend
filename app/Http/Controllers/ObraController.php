<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

use App\Models\Obra;
use App\Models\Outcome;
use App\Models\Document;

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
        $obra = Obra::with(['client', 'budget', 'incomes', 'outcomes', 'documents', 'additionals.user'])->find($id);

        $outcomes = Outcome::where('obra_id', $id)
                        ->whereNotNull('contractor_id')
                        ->with('contractor')
                        ->get();
        $contractors = $outcomes->pluck('contractor')->unique('id');
        $obra->contractors = $contractors;
        
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
        $data = $request->except('image');
        $obra->update($data);
        
        if ($request->hasFile('new_image')) {
            $image = $request->file('new_image');
            $directory = 'public/uploads/obras/'.$obra->id;
            $imageName = 'image.' . $image->extension();
            $imagePath = Storage::putFileAs($directory, $image, $imageName, 'public');
            $obra->image = Storage::url($imagePath);

            $absolutePathToDirectory = storage_path('app/'.$directory);
            chmod($absolutePathToDirectory, 0755);
            $obra->save();
        }

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

    /**
     * Store a document for an obra
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function documents(Request $request, int $id): Response
    {
        $obra = Obra::find($id);
        $name = $request->input('name');
        $category = $request->input('category');
        $document = $request->file('file');

        $directory = 'public/uploads/obras/'.$obra->id;
        $documentName = $document->getClientOriginalName();
        $documentPath = Storage::putFileAs($directory, $document, $documentName, 'public');

        $obra->documents()->create([
            'name' => $name,
            'category' => $category,
            'path' => Storage::url($documentPath),
        ]);

        $absolutePathToDirectory = storage_path('app/'.$directory);
        chmod($absolutePathToDirectory, 0755);

        return response(['message' => 'Document uploaded'], 201);
    }

    /**
     * Delete a document for an obra
     *
     * @param int $id
     * @param int $documentId
     * @return Response
     */
    public function deleteDocument(int $id, int $documentId): Response
    {
        $obra = Obra::find($id);
        $document = $obra->documents()->find($documentId);
        $document->delete();

        $absolutePathToFile = storage_path('app/'.$document->path);
        unlink($absolutePathToFile);

        return response(['message' => 'Document deleted'], 204);
    }

    /**
     * Create a additional for an obra
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function additionals(Request $request, int $id): Response
    {
        $obra = Obra::find($id);
        $obra->additionals()->create($request->all());
        return response(['message' => 'Additional created'], 201);
    }
}
