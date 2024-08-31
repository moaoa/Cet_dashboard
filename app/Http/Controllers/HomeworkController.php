<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class HomeworkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $homework = Homework::all();
        return response()->json($homework);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'url' => 'required',
        ]);

        $homework = Homework::create($request->all());
        return response()->json($homework, 201);
    }

    public function uploadHomeworkAnswer(Request $request): JsonResponse
    {
        // Validate the file inputs
        $request->validate([
            'files.*' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048', // Example validation rules
        ]);

        if(!$request->hasFile('files')) return response()->json(['message' => 'No files uploaded'], 400);

        $uploadedFiles = [];


        // foreach ($request->file('files') as $file) {
            // Get the original file name
            $file = $request->file('files');
            $fileName = $file->getClientOriginalName();
            $fileName = str_replace(' ', '_', $fileName);

            // Define a path to store the file
            $destinationPath = '/uploads/files'; // You can change this path

            // Store the file
            $path = $file->storeAs($destinationPath, $fileName, 'public');

            // Add the path to the array of uploaded files
            $uploadedFiles[] = url(asset($path));
        // }

            // Return a response with the paths of the uploaded files
            return response()->json([
                'message' => 'Files uploaded successfully',
                'file_paths' => $uploadedFiles
            ]);

    }

    /**
     * Display the specified resource.
     *
     * @param Homework $homework
     * @return JsonResponse
     */
    public function show(Homework $homework): JsonResponse
    {
        return response()->json($homework);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Homework $homework
     * @return JsonResponse
     */
    public function update(Request $request, Homework $homework): JsonResponse
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'url' => 'required',
        ]);

        $homework->update($request->all());
        return response()->json($homework);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Homework $homework
     * @return JsonResponse
     */
    public function destroy(Homework $homework): JsonResponse
    {
        $homework->delete();
        return response()->json(null, 204);
    }
}
