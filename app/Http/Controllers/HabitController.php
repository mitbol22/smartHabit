<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Habit;
use App\Http\Requests\StoreHabitRequest;
use Illuminate\Support\Facades\Auth;

class HabitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return redirect()->route('dashboard');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('habits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreHabitRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Habit::create($data);

        return redirect()->route('habits.index')->with('success', 'Habit created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Habit $habit)
    {
        $this->authorize('view', $habit);
        return view('habits.show', compact('habit'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Habit $habit)
    {
        $this->authorize('update', $habit);
        return view('habits.edit', compact('habit'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreHabitRequest $request, Habit $habit)
    {
        $this->authorize('update', $habit);
        $habit->update($request->validated());
        return redirect()->route('dashboard')->with('success', 'Habit updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Habit $habit)
    {
        $this->authorize('delete', $habit); // Assuming you have a policy for habits

        $habit->delete();

        return redirect()->route('dashboard')->with('success', 'Habit deleted successfully.');
    }
}
