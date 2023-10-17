<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Fee;
use App\Models\FeesCategory;
use App\Models\Student;
use App\Models\StudentEnroll;
use App\Models\Transaction;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Toastr;

class FeesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Module Data
        $this->title = trans_choice('module_fees_report', 1);
        $this->route = 'student.fees';
        $this->view = 'student.fees';
        $this->path = 'fees';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $data['title'] = $this->title;
        $data['route'] = $this->route;
        $data['view'] = $this->view;
        $data['path'] = $this->path;

        $data['user'] = $user = Student::where('id', Auth::guard('student')->user()->id)->firstOrFail();

        $data['sessions'] = StudentEnroll::where('student_id', $user->id)->groupBy('session_id')->get();
        $data['semesters'] = StudentEnroll::where('student_id', $user->id)->groupBy('semester_id')->get();
        $data['categories'] = FeesCategory::where('status', '1')->orderBy('title', 'asc')->get();

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

        if (! empty($request->category) || $request->category != null) {
            $data['selected_category'] = $category = $request->category;
        } else {
            $data['selected_category'] = '0';
        }

        // Filter Assignment
        $fees = Fee::with('studentEnroll')->whereHas('studentEnroll', function ($query) use ($user, $session, $semester) {
            $query->where('student_id', $user->id);
            if ($session != 0) {
                $query->where('session_id', $session);
            }
            if ($semester != 0) {
                $query->where('semester_id', $semester);
            }
        });
        if (! empty($request->category)) {
            $fees->where('category_id', $category);
        }
        $data['rows'] = $fees->where('status', '<=', '1')->orderBy('assign_date', 'desc')->get();

        return view($this->view.'.index', $data);
    }

    public function pay(Request $request)
    {
        // Field Validation
        $request->validate([
            'pay_date' => 'required|date|before_or_equal:today',
            'payment_method' => 'required',
            'fee_amount' => 'required|numeric',
            'discount_amount' => 'required|numeric',
            'fine_amount' => 'required|numeric',
            'paid_amount' => 'required|numeric',
        ]);

        $fee = Fee::find($request->fee_id);

        // Discount Calculation
        $discount_amount = 0;
        $today = date('Y-m-d');

        if (isset($fee->category)) {
            foreach ($fee->category->discounts->where('status', '1') as $discount) {

                $availability = \App\Models\FeesDiscount::availability($discount->id, $fee->studentEnroll->student_id);

                if (isset($availability)) {
                    if ($discount->start_date <= $today && $discount->end_date >= $today) {
                        if ($discount->type == '1') {
                            $discount_amount = $discount_amount + $discount->amount;
                        } else {
                            $discount_amount = $discount_amount + (($fee->fee_amount / 100) * $discount->amount);
                        }
                    }
                }
            }
        }

        // Fine Calculation
        $fine_amount = 0;
        if (empty($fee->pay_date) || $fee->due_date < $fee->pay_date) {

            $due_date = strtotime($fee->due_date);
            $today = strtotime(date('Y-m-d'));
            $days = (int) (($today - $due_date) / 86400);

            if ($fee->due_date < date('Y-m-d')) {
                if (isset($fee->category)) {
                    foreach ($fee->category->fines->where('status', '1') as $fine) {
                        if ($fine->start_day <= $days && $fine->end_day >= $days) {
                            if ($fine->type == '1') {
                                $fine_amount = $fine_amount + $fine->amount;
                            } else {
                                $fine_amount = $fine_amount + (($fee->fee_amount / 100) * $fine->amount);
                            }
                        }
                    }
                }
            }
        }

        // Net Amount Calculation
        $net_amount = ($fee->fee_amount - $discount_amount) + $fine_amount;

        DB::beginTransaction();
        // Update Data
        // $fee->fee_amount = $request->fee_amount;
        $fee->discount_amount = $discount_amount;
        $fee->fine_amount = $fine_amount;
        $fee->paid_amount = $net_amount;
        $fee->pay_date = $request->pay_date;
        $fee->payment_method = $request->payment_method;
        $fee->note = $request->note;
        $fee->status = '1';
        $fee->updated_by = Auth::guard('web')->user()->id;
        $fee->save();

        // Transaction
        $transaction = new Transaction;
        $transaction->transaction_id = Str::random(16);
        $transaction->amount = $net_amount;
        $transaction->type = '1';
        $transaction->created_by = Auth::guard('web')->user()->id;
        $fee->studentEnroll->student->transactions()->save($transaction);
        DB::commit();

        Toastr::success(__('msg_updated_successfully'), __('msg_success'));

        return redirect()->back()->with('receipt', $fee->id);
    }
}
