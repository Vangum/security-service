<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVisitorRequest;
use Illuminate\Http\RedirectResponse;
use Inertia\Response;
use App\Models\Visitor;
use App\Models\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class VisitorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $visitors = Visitor::with(['department', 'document'])->orderByDesc('entry_datetime')->get();

        return Inertia::render('Visitor/Index', [
            'visitors' => $visitors,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return Inertia::render('Visitor/Create', [
            'departments' => Department::all(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVisitorRequest $request): RedirectResponse
    {
        try {
            DB::transaction(function () use ($request) {
                $data = $request->validated();

                $visitor = Visitor::create([
                    'full_name' => $data['full_name'],
                    'department_id' => $data['department'],
                    'birth_date' => $data['birth_date'],
                    'position' => $data['position'],
                    'phone' => $data['phone'],
                    'entry_datetime' => $data['entry_datetime'],
                    'exit_datetime' => $data['exit_datetime'],
                    'remarks' => $data['remarks'] ?? null,
                    'created_by' => auth()->id(),
                ]);

                $documentData = match ($data['document_type']) {
                    'passport' => [
                        'type' => 'passport',
                        'passport_series' => $data['passport_series'] ?? null,
                        'passport_number' => $data['passport_number'] ?? null,
                        'passport_issue_date' => $data['passport_issue_date'] ?? null,
                        'passport_issued_by' => $data['passport_issued_by'] ?? null,
                        'passport_department_code' => $data['passport_department_code'] ?? null,
                    ],
                    'license' => [
                        'type' => 'license',
                        'license_series_number' => $data['license_series_number'] ?? null,
                        'license_issue_date' => $data['license_issue_date'] ?? null,
                        'license_region' => $data['license_region'] ?? null,
                        'license_issued_by' => $data['license_issued_by'] ?? null,
                    ],
                    'other' => [
                        'type' => 'other',
                        'other_document_name' => $data['other_document_name'] ?? null,
                        'other_series_number' => $data['other_series_number'] ?? null,
                        'other_series_number_original' => $data['other_series_number'] ?? null,
                        'other_issue_date' => $data['other_issue_date'] ?? null,
                        'other_issued_by' => $data['other_issued_by'] ?? null,
                    ]
                };

                $visitor->document()->create($documentData);
            });

            return Redirect::route('visitorsIndex');
        } catch (\Throwable $th) {
            return Redirect::back()->withErrors(['error' => 'Ошибка при добавлении посетителя. Попробуйте еще раз.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Visitor $visitor)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visitor $visitor)
    {
        dd($visitor);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visitor $visitor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visitor $visitor)
    {
        dd($visitor);
    }
}
