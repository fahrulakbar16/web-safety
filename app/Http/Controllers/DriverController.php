<?php

namespace App\Http\Controllers;

use App\Actions\Driver\DeleteDriverAction;
use App\Actions\Driver\GetDriversAction;
use App\Actions\Driver\StoreDriverAction;
use App\Actions\Driver\UpdateDriverAction;
use App\Http\Requests\Driver\StoreDriverRequest;
use App\Http\Requests\Driver\UpdateDriverRequest;
use App\Http\Resources\DriverResource;
use App\Models\Company;
use App\Models\Driver;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Gate::allows('drivers.view'), 403, 'Anda tidak memiliki akses untuk melihat data driver');

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');
        $companyId = $request->input('company_id');

        $result = app(GetDriversAction::class)->execute($request);
        $drivers = $result['drivers'];
        $statusCounts = $result['statusCounts'];

        $companies = Company::where('status', 'active')->get();

        return Inertia::render('Admin/Drivers/Index', [
            'drivers' => $drivers,
            'companies' => $companies,
            'search' => $search,
            'status' => $status,
            'company_id' => $companyId,
            'statusCounts' => $statusCounts,
            'sortBy' => $sortBy,
            'sortDirection' => $sortDirection,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Gate::allows('drivers.create'), 403, 'Anda tidak memiliki akses untuk menambah driver');

        $companies = Company::where('status', 'active')->get();

        return Inertia::render('Admin/Drivers/Create', [
            'companies' => $companies,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDriverRequest $request)
    {
        abort_unless(Gate::allows('drivers.create'), 403, 'Anda tidak memiliki akses untuk menambah driver');

        app(StoreDriverAction::class)->execute($request, $request->validated());

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Driver $driver)
    {
        abort_unless(Gate::allows('drivers.view'), 403, 'Anda tidak memiliki akses untuk melihat detail driver');

        $driver->load(['user', 'company']);

        return Inertia::render('Admin/Drivers/Show', [
            'driver' => (new DriverResource($driver))->resolve(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Driver $driver)
    {
        abort_unless(Gate::allows('drivers.edit'), 403, 'Anda tidak memiliki akses untuk mengubah driver');

        $driver->load(['user', 'company']);
        $companies = Company::where('status', 'active')->get();

        return Inertia::render('Admin/Drivers/Edit', [
            'driver' => (new DriverResource($driver))->resolve(),
            'companies' => $companies,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDriverRequest $request, Driver $driver)
    {
        abort_unless(Gate::allows('drivers.edit'), 403, 'Anda tidak memiliki akses untuk mengubah driver');

        app(UpdateDriverAction::class)->execute($driver, $request, $request->validated());

        return redirect()->route('drivers.index')->with('success', 'Driver berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Driver $driver)
    {
        abort_unless(Gate::allows('drivers.delete'), 403, 'Anda tidak memiliki akses untuk menghapus driver');

        try {
            app(DeleteDriverAction::class)->execute($driver);

            return redirect()->back()->with('success', 'Driver berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus driver: ' . $e->getMessage());
        }
    }
}

