<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        return Inertia::render('Department/Index', [
            'departments' => Department::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request): RedirectResponse
    {
        try {
            $data = $request->validated();
            Department::create($data);

            return Redirect::route('departmentsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при добавлении справочника. Попробуйте еще раз.']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department): RedirectResponse
    {
        try {
            $data = $request->validated();
            $department->update($data);
            return Redirect::route('departmentsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при изменении справочника. Попробуйте еще раз.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department): RedirectResponse
    {
        try {
            $department->update([
                'deleted_by' => auth()->id(),
            ]);
            $department->delete();
            return Redirect::route('departmentsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при удалении справочника. Попробуйте еще раз.']);
        }
    }
}
