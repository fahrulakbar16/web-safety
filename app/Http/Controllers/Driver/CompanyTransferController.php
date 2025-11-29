<?php

namespace App\Http\Controllers\Driver;

use App\Actions\Driver\StoreCompanyTransferAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Driver\StoreCompanyTransferRequest;
use App\Models\Company;
use App\Models\Event;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class CompanyTransferController extends Controller
{
    /**
     * Display the company transfer form.
     */
    public function index()
    {
        abort_unless(Gate::allows('driver.assessment.view'), 403, 'Anda tidak memiliki akses untuk melihat halaman pergantian PT.');

        $user = Auth::user();
        $driver = $user->drivers()->with('company')->first();

        if (!$driver) {
            return redirect()->route('profile.show')
                ->with('error', 'Data driver tidak ditemukan.');
        }

        // Get all active companies except current company
        $companies = Company::where('status', 'active')
            ->where('id', '!=', $driver->company_id)
            ->get();

        return Inertia::render('Driver/CompanyTransfer/Index', [
            'driver' => [
                'id' => $driver->id,
                'name' => $driver->name,
                'company' => [
                    'id' => $driver->company->id,
                    'name' => $driver->company->name,
                ],
            ],
            'companies' => $companies,
        ]);
    }

    /**
     * Store a new company transfer request.
     */
    public function store(StoreCompanyTransferRequest $request)
    {
        try {
            $transfer = app(StoreCompanyTransferAction::class)->execute($request);

            // Get first active event and exam for quiz
            $event = Event::where('status', 'active')
                ->with(['exams' => function ($query) {
                    $query->orderBy('id', 'asc');
                }])
                ->first();

            if (!$event || $event->exams->isEmpty()) {
                return redirect()->back()
                    ->with('error', 'Tidak ada event atau exam yang tersedia untuk quiz. Silakan hubungi administrator.');
            }

            $exam = $event->exams->first();

            // Redirect to quiz with transfer ID
            return redirect()
                ->route('driver.company-transfer.quiz', ['transfer' => $transfer->id, 'event' => $event->id, 'exam' => $exam->id])
                ->with('info', 'Silakan ikuti quiz ulang terlebih dahulu sebelum menyelesaikan pengajuan pergantian PT.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show quiz for company transfer.
     */
    public function showQuiz(Request $request, $transfer, Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('driver.assessment.view'), 403, 'Anda tidak memiliki akses untuk melihat quiz.');

        $user = Auth::user();
        $driver = $user->drivers()->first();

        if (!$driver) {
            return redirect()->route('profile.show')
                ->with('error', 'Data driver tidak ditemukan.');
        }

        // Verify transfer belongs to current driver
        $transferModel = \App\Models\DriverCompanyTransfer::where('id', $transfer)
            ->where('driver_id', $driver->id)
            ->where('status', 'waiting_quiz')
            ->first();

        if (!$transferModel) {
            return redirect()->route('driver.company-transfer.index')
                ->with('error', 'Pengajuan pergantian PT tidak ditemukan atau sudah diproses.');
        }

        try {
            $data = app(\App\Actions\Assessment\GetExamDetailAction::class)->execute($event, $exam);
            $data['transfer_id'] = $transferModel->id;

            return Inertia::render('Driver/CompanyTransfer/Quiz', $data);
        } catch (\Exception $e) {
            return redirect()->route('driver.company-transfer.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Submit quiz and redirect to upload screenshot if passed.
     */
    public function submitQuiz(Request $request, $transfer, Event $event, Exam $exam)
    {
        $user = Auth::user();
        $driver = $user->drivers()->first();

        if (!$driver) {
            return redirect()->route('profile.show')
                ->with('error', 'Data driver tidak ditemukan.');
        }

        // Verify transfer belongs to current driver
        $transferModel = \App\Models\DriverCompanyTransfer::where('id', $transfer)
            ->where('driver_id', $driver->id)
            ->where('status', 'waiting_quiz')
            ->first();

        if (!$transferModel) {
            return redirect()->route('driver.company-transfer.index')
                ->with('error', 'Pengajuan pergantian PT tidak ditemukan atau sudah diproses.');
        }

        try {
            // Submit exam first
            $examData = app(\App\Actions\Assessment\SubmitExamAction::class)->execute($request, $event, $exam);

            // Check if exam passed
            $isPassed = $examData['examResult']['is_passed'] ?? false;

            if (!$isPassed) {
                // Return to quiz page with result
                return Inertia::render('Driver/CompanyTransfer/Quiz', array_merge($examData, [
                    'transfer_id' => $transferModel->id,
                ]));
            }

            // If passed, redirect to upload screenshot page
            return redirect()->route('driver.company-transfer.upload-screenshot', ['transfer' => $transferModel->id])
                ->with('success', 'Selamat! Quiz berhasil diselesaikan. Silakan upload screenshot hasil quiz untuk menyelesaikan pengajuan pergantian PT.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show upload screenshot page after quiz.
     */
    public function showUploadScreenshot($transfer)
    {
        abort_unless(Gate::allows('driver.assessment.view'), 403, 'Anda tidak memiliki akses untuk melihat halaman ini.');

        $user = Auth::user();
        $driver = $user->drivers()->first();

        if (!$driver) {
            return redirect()->route('profile.show')
                ->with('error', 'Data driver tidak ditemukan.');
        }

        // Verify transfer belongs to current driver and quiz is passed
        $transferModel = \App\Models\DriverCompanyTransfer::where('id', $transfer)
            ->where('driver_id', $driver->id)
            ->where('status', 'waiting_quiz')
            ->with(['oldCompany', 'newCompany'])
            ->first();

        if (!$transferModel) {
            return redirect()->route('driver.company-transfer.index')
                ->with('error', 'Pengajuan pergantian PT tidak ditemukan atau sudah diproses.');
        }

        return Inertia::render('Driver/CompanyTransfer/UploadScreenshot', [
            'transfer' => [
                'id' => $transferModel->id,
                'old_company' => [
                    'id' => $transferModel->oldCompany->id,
                    'name' => $transferModel->oldCompany->name,
                ],
                'new_company' => [
                    'id' => $transferModel->newCompany->id,
                    'name' => $transferModel->newCompany->name,
                ],
            ],
        ]);
    }

    /**
     * Upload screenshot after quiz.
     */
    public function uploadScreenshot(Request $request, $transfer)
    {
        $request->validate([
            'screenshot_quiz' => ['required', 'file', 'mimes:jpg,jpeg,png', 'max:5120'],
        ]);

        $user = Auth::user();
        $driver = $user->drivers()->first();

        if (!$driver) {
            return redirect()->route('profile.show')
                ->with('error', 'Data driver tidak ditemukan.');
        }

        // Verify transfer belongs to current driver
        $transferModel = \App\Models\DriverCompanyTransfer::where('id', $transfer)
            ->where('driver_id', $driver->id)
            ->where('status', 'waiting_quiz')
            ->first();

        if (!$transferModel) {
            return redirect()->route('driver.company-transfer.index')
                ->with('error', 'Pengajuan pergantian PT tidak ditemukan atau sudah diproses.');
        }

        try {
            // Handle screenshot upload
            if ($request->hasFile('screenshot_quiz')) {
                $file = $request->file('screenshot_quiz');
                $screenshotPath = $file->store('company-transfers/screenshot-quiz', 'public');

                // Update transfer with screenshot and change status to pending
                $transferModel->update([
                    'screenshot_quiz' => $screenshotPath,
                    'status' => 'pending',
                ]);

                return redirect()->route('driver.company-transfer.index')
                    ->with('success', 'Screenshot hasil quiz berhasil diupload. Pengajuan pergantian PT telah dikirim dan menunggu persetujuan administrator.');
            }
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', $e->getMessage())
                ->withInput();
        }
    }
}
