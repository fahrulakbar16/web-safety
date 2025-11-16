<?php

namespace App\Http\Controllers;

use App\Actions\Company\DeleteCompanyAction;
use App\Actions\Company\GetCompaniesAction;
use App\Actions\Company\StoreCompanyAction;
use App\Actions\Company\UpdateCompanyAction;
use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Gate::allows('companies.view'), 403, 'Anda tidak memiliki akses untuk melihat data perusahaan');

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');

        $result = app(GetCompaniesAction::class)->execute($request);
        $companies = $result['companies'];
        $statusCounts = $result['statusCounts'];

        return Inertia::render('Admin/Companies/Index', [
            'companies' => $companies,
            'search' => $search,
            'status' => $status,
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
        abort_unless(Gate::allows('companies.create'), 403, 'Anda tidak memiliki akses untuk menambah perusahaan');

        return Inertia::render('Admin/Companies/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        abort_unless(Gate::allows('companies.create'), 403, 'Anda tidak memiliki akses untuk menambah perusahaan');

        app(StoreCompanyAction::class)->execute($request, $request->validated());

        return redirect()->back()->with('success', 'Perusahaan berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        abort_unless(Gate::allows('companies.view'), 403, 'Anda tidak memiliki akses untuk melihat detail perusahaan');

        return Inertia::render('Admin/Companies/Show', [
            'company' => (new CompanyResource($company))->resolve(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {
        abort_unless(Gate::allows('companies.edit'), 403, 'Anda tidak memiliki akses untuk mengubah perusahaan');

        return Inertia::render('Admin/Companies/Edit', [
            'company' => (new CompanyResource($company))->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        abort_unless(Gate::allows('companies.edit'), 403, 'Anda tidak memiliki akses untuk mengubah perusahaan');

        app(UpdateCompanyAction::class)->execute($company, $request, $request->validated());

        return redirect()->back()->with('success', 'Perusahaan berhasil diperbarui');
    }

    /**
     * Quick store a company (for dropdown creation).
     */
    public function quickStore(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $company = app(StoreCompanyAction::class)->execute($request, [
            'name' => $request->name,
            'alamat' => null,
            'logo' => null,
            'status' => 'active',
        ]);

        return response()->json([
            'success' => true,
            'company' => (new CompanyResource($company))->resolve(),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        abort_unless(Gate::allows('companies.delete'), 403, 'Anda tidak memiliki akses untuk menghapus perusahaan');

        try {
            app(DeleteCompanyAction::class)->execute($company);

            return redirect()->back()->with('success', 'Perusahaan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus perusahaan: ' . $e->getMessage());
        }
    }
}

