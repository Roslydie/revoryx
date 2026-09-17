<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Testimonial::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'prenom' => 'required|string',
            'message' => 'required|string',
        ]);

        $testimonial = Testimonial::create($validated);
        return response()->json($testimonial, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json($testimonial);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string',
            'prenom' => 'sometimes|required|string',
            'message' => 'sometimes|required|string',
            'published' => 'sometimes|boolean',
        ]);

        $testimonial->update($validated);
        return response()->json($testimonial);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return response()->json(['message' => 'Testimonial deleted'], 200);
    }

    /**
     * Publish or unpublish a testimonial
     */
    public function publish(Testimonial $testimonial)
    {
        $testimonial->update(['published' => !$testimonial->published]);
        return response()->json($testimonial);
    }

    /**
     * Get published testimonials for frontend
     */
    public function published()
    {
        return response()->json(Testimonial::where('published', true)->get());
    }
}
