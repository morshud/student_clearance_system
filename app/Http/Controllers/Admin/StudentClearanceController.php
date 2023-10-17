<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\Fee;
use App\Models\Grade;
use App\Models\Program;
use App\Models\Section;
use App\Models\Semester;
use App\Models\Session;
use App\Models\StatusType;
use App\Models\Student;
use App\Models\StudentClearance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Toastr;

class StudentClearanceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = 'Student Clearance';
        $this->route = 'admin.student-clearance';
        $this->view = 'admin.student-clearance';
        $this->path = 'student-clearance';
        $this->access = 'student';

        $this->middleware('permission:'.$this->access.'-view|'.$this->access.'-create|'.$this->access.'-edit|'.$this->access.'-delete|'.$this->access.'-card', ['only' => ['index', 'show', 'status', 'sendPassword']]);
        $this->middleware('permission:'.$this->access.'-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:'.$this->access.'-edit', ['only' => ['edit', 'update', 'status']]);
        $this->middleware('permission:'.$this->access.'-delete', ['only' => ['destroy']]);
        $this->middleware('permission:'.$this->access.'-password-print', ['only' => ['printPassword']]);
        $this->middleware('permission:'.$this->access.'-password-change', ['only' => ['passwordChange']]);
        $this->middleware('permission:'.$this->access.'-card', ['only' => ['index', 'card']]);
        $this->middleware('permission:'.$this->access.'-import', ['only' => ['index', 'import', 'importStore']]);

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;
        $data['access'] = $this->access;

        if (! empty($request->faculty) || $request->faculty != null) {
            $data['selected_faculty'] = $faculty = $request->faculty;
        } else {
            $data['selected_faculty'] = $faculty = '0';
        }

        if (! empty($request->program) || $request->program != null) {
            $data['selected_program'] = $program = $request->program;
        } else {
            $data['selected_program'] = $program = '0';
        }

        if (! empty($request->session) || $request->session != null) {
            $data['selected_session'] = $session = $request->session;
        } else {
            $data['selected_session'] = $session = '0';
        }

        if (! empty($request->semester) || $request->semester != null) {
            $data['selected_semester'] = $semester = $request->semester;
        } else {
            $data['selected_semester'] = $semester = '0';
        }

        if (! empty($request->section) || $request->section != null) {
            $data['selected_section'] = $section = $request->section;
        } else {
            $data['selected_section'] = $section = '0';
        }

        if (! empty($request->status) || $request->status != null) {
            $data['selected_status'] = $status = $request->status;
        } else {
            $data['selected_status'] = '0';
        }

        if (! empty($request->student_id) || $request->student_id != null) {
            $data['selected_student_id'] = $student_id = $request->student_id;
        } else {
            $data['selected_student_id'] = null;
        }

        if (! empty($request->date) || $request->date != null) {
            $data['selected_date'] = $date = $request->date;
        } else {
            $data['selected_date'] = date('Y-m-d', strtotime(Carbon::today()));
        }

        // Search Filter
        $data['faculties'] = Faculty::where('status', '1')->orderBy('title', 'asc')->get();
        $data['statuses'] = StatusType::where('status', '1')->orderBy('title', 'asc')->get();

        if (! empty($request->faculty) && $request->faculty != '0') {
            $data['programs'] = Program::where('faculty_id', $faculty)->where('status', '1')->orderBy('title', 'asc')->get();
        }

        if (! empty($request->program) && $request->program != '0') {
            $sessions = Session::where('status', 1);
            $sessions->with('programs')->whereHas('programs', function ($query) use ($program) {
                $query->where('program_id', $program);
            });
            $data['sessions'] = $sessions->orderBy('id', 'desc')->get();
        }

        if (! empty($request->program) && $request->program != '0') {
            $semesters = Semester::where('status', 1);
            $semesters->with('programs')->whereHas('programs', function ($query) use ($program) {
                $query->where('program_id', $program);
            });
            $data['semesters'] = $semesters->orderBy('id', 'asc')->get();
        }

        if (! empty($request->program) && $request->program != '0' && ! empty($request->semester) && $request->semester != '0') {
            $sections = Section::where('status', 1);
            $sections->with('semesterPrograms')->whereHas('semesterPrograms', function ($query) use ($program, $semester) {
                $query->where('program_id', $program);
                $query->where('semester_id', $semester);
            });
            $data['sections'] = $sections->orderBy('title', 'asc')->get();
        }

        // Student Filter
        $students = Student::where('status', '1');
        if ($faculty != 0) {
            $students->with('program')->whereHas('program', function ($query) use ($faculty) {
                $query->where('faculty_id', $faculty);
            });
        }
        $students->with('currentEnroll')->whereHas('currentEnroll', function ($query) use ($program, $session, $semester, $section) {
            if ($program != 0) {
                $query->where('program_id', $program);
            }
            if ($session != 0) {
                $query->where('session_id', $session);
            }
            if ($semester != 0) {
                $query->where('semester_id', $semester);
            }
            if ($section != 0) {
                $query->where('section_id', $section);
            }
        });
        if (! empty($request->status)) {
            $students->with('statuses')->whereHas('statuses', function ($query) use ($status) {
                $query->where('status_type_id', $status);
            });
        }
        if (! empty($request->student_id)) {
            $students->where('student_id', 'LIKE', '%'.$student_id.'%');
        }
        $rows = $students->orderBy('student_id', 'desc')->get();

        // Array Sorting
        $data['rows'] = $rows->sortByDesc(function ($query) {

            return $query->student_id;

        })->all();

        return view($this->view.'.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;
        $data['access'] = $this->access;

        $student = Student::where('id', $id)->first();
        $data['row'] = $student;

        //dd($student);

        $data['fees'] = Fee::with('studentEnroll')->whereHas('studentEnroll', function ($query) use ($student) {
            $query->where('student_id', $student->id);
        })
            ->orderBy('id', 'desc')->get();

        $data['grades'] = Grade::where('status', '1')->orderBy('min_mark', 'desc')->get();

        //dd($data);

        return view($this->view.'.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Field Validation
        $request->validate([
            'status' => 'required',
        ]);

        $clr = StudentClearance::findOrFail($id);
        if (isset($request->reject_note)) {
            $clr->reject_reason = $request->reject_note;
        }
        $clr->status = $request->status;
        $clr->update();

        Toastr::success(__('Student clearance is successfully updated'), __('msg_success'));

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
