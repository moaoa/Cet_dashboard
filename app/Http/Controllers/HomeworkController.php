<?php

namespace App\Http\Controllers;

use App\Models\Homework;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

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
            'description' => 'required',
            'user_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'url' => 'required',
        ]);

        $homework = Homework::create($request->all());
        return response()->json($homework, 201);
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
