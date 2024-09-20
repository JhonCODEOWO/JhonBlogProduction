<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::with('user')->get();
        return response()->json($categories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            Log::info($request->all());
            $category = Category::create($request->all());

            return response()->json([
                "status" => 'ok',
                "message"=>'Categoría generada correctamente.'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Category $category, Request $request)
    {
        try {
            $category->update($request->all());
            return response()->json([
                "status" => 'ok',
                "message"=>'Categoría modificada correctamente'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return response()->json([
                "status" => 'ok',
                "message"=>'Categoría eliminada correctamente'
            ]);
        } catch (Exception $ex) {
            return response()->json([
                "status" => 'error',
                "message"=>$ex->getMessage()
            ]);
        }
    }
}
