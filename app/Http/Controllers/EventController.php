<?php

namespace App\Http\Controllers;

use App\Actions\Event\DeleteEventAction;
use App\Actions\Event\GetEventsAction;
use App\Actions\Event\StoreEventAction;
use App\Actions\Event\UpdateEventAction;
use App\Actions\Event\StoreEventMateriAction;
use App\Actions\Event\UpdateEventMateriAction;
use App\Actions\Event\DeleteEventMateriAction;
use App\Actions\Event\StoreExamAction;
use App\Actions\Event\UpdateExamAction;
use App\Actions\Event\DeleteExamAction;
use App\Http\Requests\Event\StoreEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use App\Http\Requests\Event\StoreEventMateriRequest;
use App\Http\Requests\Event\UpdateEventMateriRequest;
use App\Http\Requests\Event\StoreExamRequest;
use App\Http\Requests\Event\UpdateExamRequest;
use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\EventMateri;
use App\Models\Exam;
use App\Models\ExamQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        abort_unless(Gate::allows('events.view'), 403, 'Anda tidak memiliki akses untuk melihat data event');

        $sortBy = $request->get('sortBy', 'created_at');
        $sortDirection = $request->get('sortDirection', 'desc');
        $search = $request->input('search');
        $status = $request->input('status');

        $result = app(GetEventsAction::class)->execute($request);
        $events = $result['events'];
        $statusCounts = $result['statusCounts'];

        return Inertia::render('Admin/Events/Index', [
            'events' => $events,
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
        abort_unless(Gate::allows('events.create'), 403, 'Anda tidak memiliki akses untuk menambah event');

        return Inertia::render('Admin/Events/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEventRequest $request)
    {
        abort_unless(Gate::allows('events.create'), 403, 'Anda tidak memiliki akses untuk menambah event');

        app(StoreEventAction::class)->execute($request, $request->validated());

        return redirect()->route('events.index')->with('success', 'Event berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        abort_unless(Gate::allows('events.view'), 403, 'Anda tidak memiliki akses untuk melihat detail event');

        $event->load(['eventMateris', 'exams']);

        return Inertia::render('Admin/Events/Show', [
            'event' => (new EventResource($event))->resolve(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah event');

        return Inertia::render('Admin/Events/Edit', [
            'event' => (new EventResource($event))->resolve(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEventRequest $request, Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah event');

        app(UpdateEventAction::class)->execute($event, $request, $request->validated());

        return redirect()->route('events.index')->with('success', 'Event berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        abort_unless(Gate::allows('events.delete'), 403, 'Anda tidak memiliki akses untuk menghapus event');

        try {
            app(DeleteEventAction::class)->execute($event);

            return redirect()->back()->with('success', 'Event berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus event: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new event materi.
     */
    public function createMateri(Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah materi');

        return Inertia::render('Admin/Events/CreateMateri', [
            'event' => (new EventResource($event))->resolve(),
        ]);
    }

    /**
     * Store event materi.
     */
    public function storeMateri(StoreEventMateriRequest $request, Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah materi');

        $data = $request->validated();
        $data['event_id'] = $event->id;

        app(StoreEventMateriAction::class)->execute($request, $data);

        return redirect()->route('events.show', $event->id)->with('success', 'Materi berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified event materi.
     */
    public function editMateri(Event $event, EventMateri $materi)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah materi');

        $event->load('eventMateris');
        $materi->load('event');

        return Inertia::render('Admin/Events/EditMateri', [
            'event' => (new EventResource($event))->resolve(),
            'materi' => [
                'id' => $materi->id,
                'urutan' => $materi->urutan,
                'name' => $materi->name,
                'type' => $materi->type,
                'description' => $materi->description,
                'file' => $materi->file ? url('storage/' . $materi->file) : null,
            ],
        ]);
    }

    /**
     * Update event materi.
     */
    public function updateMateri(UpdateEventMateriRequest $request, Event $event, EventMateri $materi)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah materi');

        app(UpdateEventMateriAction::class)->execute($materi, $request, $request->validated());

        return redirect()->route('events.show', $event->id)->with('success', 'Materi berhasil diperbarui');
    }

    /**
     * Delete event materi.
     */
    public function destroyMateri(Event $event, EventMateri $materi)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menghapus materi');

        try {
            app(DeleteEventMateriAction::class)->execute($materi);

            return redirect()->back()->with('success', 'Materi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus materi: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new exam.
     */
    public function createExam(Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah ujian');

        return Inertia::render('Admin/Events/CreateExam', [
            'event' => (new EventResource($event))->resolve(),
        ]);
    }

    /**
     * Store exam.
     */
    public function storeExam(StoreExamRequest $request, Event $event)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah ujian');

        $data = $request->validated();
        $data['event_id'] = $event->id;

        app(StoreExamAction::class)->execute($data);

        return redirect()->route('events.show', $event->id)->with('success', 'Ujian berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified exam.
     */
    public function editExam(Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah ujian');

        $event->load('exams');
        $exam->load('event');

        return Inertia::render('Admin/Events/EditExam', [
            'event' => (new EventResource($event))->resolve(),
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'durasi' => $exam->durasi,
                'jumlah_soal' => $exam->jumlah_soal,
                'minimal_score' => $exam->minimal_score,
            ],
        ]);
    }

    /**
     * Update exam.
     */
    public function updateExam(UpdateExamRequest $request, Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah ujian');

        app(UpdateExamAction::class)->execute($exam, $request->validated());

        return redirect()->route('events.show', $event->id)->with('success', 'Ujian berhasil diperbarui');
    }

    /**
     * Display the specified exam.
     */
    public function showExam(Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.view'), 403, 'Anda tidak memiliki akses untuk melihat detail ujian');

        $exam->load(['event', 'examQuestions.examQuestionOptions']);

        return Inertia::render('Admin/Events/ShowExam', [
            'event' => (new EventResource($event))->resolve(),
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
                'description' => $exam->description,
                'durasi' => $exam->durasi,
                'jumlah_soal' => $exam->jumlah_soal,
                'minimal_score' => $exam->minimal_score,
                'exam_questions' => $exam->examQuestions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'urutan' => $question->urutan,
                        'soal' => $question->soal,
                        'exam_question_options' => $question->examQuestionOptions->map(function ($option) {
                            return [
                                'id' => $option->id,
                                'opsi' => $option->opsi,
                                'jawaban' => $option->jawaban,
                                'is_correct' => $option->is_correct,
                            ];
                        }),
                    ];
                })->sortBy('urutan')->values(),
            ],
        ]);
    }

    /**
     * Delete exam.
     */
    public function destroyExam(Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menghapus ujian');

        try {
            app(DeleteExamAction::class)->execute($exam);

            return redirect()->back()->with('success', 'Ujian berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus ujian: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new exam question.
     */
    public function createQuestion(Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah soal');

        $exam->load('event');

        return Inertia::render('Admin/Events/CreateQuestion', [
            'event' => (new EventResource($event))->resolve(),
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
            ],
        ]);
    }

    /**
     * Store exam question.
     */
    public function storeQuestion(Request $request, Event $event, Exam $exam)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menambah soal');

        $validated = $request->validate([
            'urutan' => ['nullable', 'integer', 'min:1'],
            'soal' => ['required', 'string'],
            'options' => ['required', 'array', 'min:2'],
            'options.*.opsi' => ['required', 'string', 'max:10'],
            'options.*.jawaban' => ['required', 'string'],
            'options.*.is_correct' => ['required', 'boolean'],
        ]);

        $question = ExamQuestion::create([
            'exam_id' => $exam->id,
            'urutan' => $validated['urutan'] ?? ($exam->examQuestions()->max('urutan') ?? 0) + 1,
            'soal' => $validated['soal'],
        ]);

        foreach ($validated['options'] as $optionData) {
            $question->examQuestionOptions()->create([
                'opsi' => $optionData['opsi'],
                'jawaban' => $optionData['jawaban'],
                'is_correct' => $optionData['is_correct'],
            ]);
        }

        return redirect()->route('events.exams.show', [$event->id, $exam->id])->with('success', 'Soal berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified exam question.
     */
    public function editQuestion(Event $event, Exam $exam, ExamQuestion $question)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah soal');

        $exam->load('event');
        $question->load('examQuestionOptions');

        return Inertia::render('Admin/Events/EditQuestion', [
            'event' => (new EventResource($event))->resolve(),
            'exam' => [
                'id' => $exam->id,
                'name' => $exam->name,
            ],
            'question' => [
                'id' => $question->id,
                'urutan' => $question->urutan,
                'soal' => $question->soal,
                'options' => $question->examQuestionOptions->map(function ($option) {
                    return [
                        'id' => $option->id,
                        'opsi' => $option->opsi,
                        'jawaban' => $option->jawaban,
                        'is_correct' => $option->is_correct,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Update exam question.
     */
    public function updateQuestion(Request $request, Event $event, Exam $exam, ExamQuestion $question)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk mengubah soal');

        $validated = $request->validate([
            'urutan' => ['nullable', 'integer', 'min:1'],
            'soal' => ['required', 'string'],
            'options' => ['required', 'array', 'min:2'],
            'options.*.id' => ['nullable', 'integer', 'exists:exam_question_options,id'],
            'options.*.opsi' => ['required', 'string', 'max:10'],
            'options.*.jawaban' => ['required', 'string'],
            'options.*.is_correct' => ['required', 'boolean'],
        ]);

        $question->update([
            'urutan' => $validated['urutan'] ?? $question->urutan,
            'soal' => $validated['soal'],
        ]);

        // Update or create options
        $existingOptionIds = [];
        foreach ($validated['options'] as $optionData) {
            if (isset($optionData['id'])) {
                $option = $question->examQuestionOptions()->find($optionData['id']);
                if ($option) {
                    $option->update([
                        'opsi' => $optionData['opsi'],
                        'jawaban' => $optionData['jawaban'],
                        'is_correct' => $optionData['is_correct'],
                    ]);
                    $existingOptionIds[] = $option->id;
                }
            } else {
                $newOption = $question->examQuestionOptions()->create([
                    'opsi' => $optionData['opsi'],
                    'jawaban' => $optionData['jawaban'],
                    'is_correct' => $optionData['is_correct'],
                ]);
                $existingOptionIds[] = $newOption->id;
            }
        }

        // Delete options that are not in the request
        $question->examQuestionOptions()->whereNotIn('id', $existingOptionIds)->delete();

        return redirect()->route('events.exams.show', [$event->id, $exam->id])->with('success', 'Soal berhasil diperbarui');
    }

    /**
     * Delete exam question.
     */
    public function destroyQuestion(Event $event, Exam $exam, ExamQuestion $question)
    {
        abort_unless(Gate::allows('events.edit'), 403, 'Anda tidak memiliki akses untuk menghapus soal');

        try {
            $question->delete();

            return redirect()->back()->with('success', 'Soal berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus soal: ' . $e->getMessage());
        }
    }
}

