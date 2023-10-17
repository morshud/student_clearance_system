<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\StudentClearance;
use App\Models\StudentEnroll;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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
        $this->route = 'student.clearance';
        $this->view = 'student.clearance';
        $this->path = 'clearance';
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

        $data['user'] = $user = Student::where('id', Auth::guard('student')->user()->id)->firstOrFail();

        $data['sessions'] = StudentEnroll::where('student_id', $user->id)->groupBy('session_id')->get();
        $data['semesters'] = StudentEnroll::where('student_id', $user->id)->groupBy('semester_id')->get();

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

        // Filter Assignment

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
        //
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
            'admission_letter' => 'required|max:2048',
            'birth_cert' => 'required|max:2048',
            'first_sitting' => 'required|max:2048',
            'school_fees_receipt' => 'required|max:2048',
            'bio_data' => 'required|max:2048',
        ]);

        if ($request->has('admission_letter')) {
            $admission_letter = Str::random(10).'.'.$request->admission_letter->extension();

            $request->admission_letter->move(public_path('student_clearance_uploads'), $admission_letter);
        } else {
            $admission_letter = '';
        }
        if ($request->has('birth_cert')) {
            $birth_cert = Str::random(10).'.'.$request->birth_cert->extension();

            $request->birth_cert->move(public_path('student_clearance_uploads'), $birth_cert);
        } else {
            $birth_cert = '';
        }
        if ($request->has('first_sitting')) {
            $first_sitting = Str::random(10).'.'.$request->first_sitting->extension();

            $request->first_sitting->move(public_path('student_clearance_uploads'), $first_sitting);
        } else {
            $first_sitting = '';
        }
        if ($request->has('second_sitting')) {
            $second_sitting = Str::random(10).'.'.$request->second_sitting->extension();

            $request->second_sitting->move(public_path('student_clearance_uploads'), $second_sitting);
        } else {
            $second_sitting = '';
        }
        if ($request->has('school_fees_receipt')) {
            $school_fees_receipt = Str::random(10).'.'.$request->school_fees_receipt->extension();

            $request->school_fees_receipt->move(public_path('student_clearance_uploads'), $school_fees_receipt);
        } else {
            $school_fees_receipt = '';
        }
        if ($request->has('bio_data')) {
            $bio_data = Str::random(10).'.'.$request->bio_data->extension();

            $request->bio_data->move(public_path('student_clearance_uploads'), $bio_data);
        } else {
            $bio_data = '';
        }

        $clr = StudentClearance::findOrFail($id);
        $clr->admission_letter = $admission_letter;
        $clr->birth_cert = $birth_cert;
        $clr->first_sitting_cert = $first_sitting;
        $clr->second_sitting_cert = $second_sitting;
        $clr->school_fees_receipt = $school_fees_receipt;
        $clr->bio_data = $bio_data;
        $clr->status = 'processing';
        $clr->update();

        Toastr::success(__('Clearance uploaded successfully!!!'), __('msg_success'));

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
