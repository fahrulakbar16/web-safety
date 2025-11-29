<?php

namespace App\Http\Controllers\Driver;

use App\Actions\Assessment\CheckDriverPassedAction;
use App\Actions\Assessment\CompleteMateriAction;
use App\Actions\Assessment\GetAssessmentDataAction;
use App\Actions\Assessment\GetExamDetailAction;
use App\Actions\Assessment\GetMateriDetailAction;
use App\Actions\Assessment\SubmitExamAction;
use App\Actions\Driver\CheckDriverBiodataCompleteAction;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMateri;
use App\Models\Exam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AssessmentController extends Controller
{
    /**
     * Display the assessment page with events and materials.
     */
    public function index()
    {
        abort_unless(Gate::allows('driver.assessment.view'), 403, 'Anda tidak memiliki akses untuk melihat assessment driver');

        // Check if driver biodata is complete
        $biodataCheck = app(CheckDriverBiodataCompleteAction::class)->execute();

        if (!$biodataCheck['is_complete']) {
            return redirect()->route('profile.show')
                ->with('warning', 'Silakan lengkapi biodata driver terlebih dahulu sebelum mengakses assessment.')
                ->with('show_biodata_modal', true)
                ->with('missing_fields', $biodataCheck['missing_required_fields']);
        }

        // Check if driver has passed all exams
        $passedCheck = app(CheckDriverPassedAction::class)->execute();

        if ($passedCheck['is_passed']) {
            return Inertia::render('Driver/Assessment/Passed', [
                'event' => $passedCheck['passed_event'],
            ]);
        }

        $events = app(GetAssessmentDataAction::class)->execute();

        return Inertia::render('Driver/Assessment/Index', [
            'events' => $events,
        ]);
    }

    /**
     * Mark a material as completed.
     */
    public function completeMateri(Request $request, EventMateri $eventMateri)
    {
        // Check if driver biodata is complete
        $biodataCheck = app(CheckDriverBiodataCompleteAction::class)->execute();

        if (!$biodataCheck['is_complete']) {
            return redirect()->route('profile.show')
                ->with('warning', 'Silakan lengkapi biodata driver terlebih dahulu sebelum menyelesaikan materi.')
                ->with('show_biodata_modal', true)
                ->with('missing_fields', $biodataCheck['missing_required_fields']);
        }

        try {
            app(CompleteMateriAction::class)->execute($eventMateri);

            return redirect()->back()->with('success', 'Materi berhasil diselesaikan!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show material detail.
     */
    public function showMateri(EventMateri $eventMateri)
    {
        // Check if driver biodata is complete
        $biodataCheck = app(CheckDriverBiodataCompleteAction::class)->execute();

        if (!$biodataCheck['is_complete']) {
            return redirect()->route('profile.show')
                ->with('warning', 'Silakan lengkapi biodata driver terlebih dahulu sebelum mengakses materi.')
                ->with('show_biodata_modal', true)
                ->with('missing_fields', $biodataCheck['missing_required_fields']);
        }

        $data = app(GetMateriDetailAction::class)->execute($eventMateri);

        return Inertia::render('Driver/Assessment/ShowMateri', $data);
    }

    /**
     * Show exam for driver to take.
     */
    public function showExam(Event $event, Exam $exam)
    {
        // Check if driver biodata is complete
        $biodataCheck = app(CheckDriverBiodataCompleteAction::class)->execute();

        if (!$biodataCheck['is_complete']) {
            return redirect()->route('profile.show')
                ->with('warning', 'Silakan lengkapi biodata driver terlebih dahulu sebelum mengikuti ujian.')
                ->with('show_biodata_modal', true)
                ->with('missing_fields', $biodataCheck['missing_required_fields']);
        }

        try {
            $data = app(GetExamDetailAction::class)->execute($event, $exam);

            return Inertia::render('Driver/Assessment/ShowExam', $data);
        } catch (\Exception $e) {
            return redirect()->route('driver.assessment.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Submit exam answers and calculate score.
     */
    public function submitExam(Request $request, Event $event, Exam $exam)
    {
        // Check if driver biodata is complete
        $biodataCheck = app(CheckDriverBiodataCompleteAction::class)->execute();

        if (!$biodataCheck['is_complete']) {
            return redirect()->route('profile.show')
                ->with('warning', 'Silakan lengkapi biodata driver terlebih dahulu sebelum mengikuti ujian.')
                ->with('show_biodata_modal', true)
                ->with('missing_fields', $biodataCheck['missing_required_fields']);
        }

        try {
            $data = app(SubmitExamAction::class)->execute($request, $event, $exam);

            return Inertia::render('Driver/Assessment/ShowExam', $data);
        } catch (\Exception $e) {
            return redirect()->route('driver.assessment.index')
                ->with('error', $e->getMessage());
        }
    }
}

