@extends('admin.layouts.master')
@section('title', $title)
@section('content')

    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-md-4">
                    <div class="card user-card user-card-1">
                        <div class="card-body pb-0">
                            @php $student = $row; @endphp
                            @php
                                $curr_enroll = \App\Models\Student::enroll($row->id);
                                $clr = \App\Models\StudentClearance::where('student_id', $row->id)->first();
                            @endphp

                            <div class="media user-about-block align-items-center mt-0 mb-3">
                                <div class="position-relative d-inline-block">
                                    @if (is_file('uploads/' . $path . '/' . $row->photo))
                                        <img src="{{ asset('uploads/' . $path . '/' . $row->photo) }}"
                                            class="img-radius img-fluid wid-80" alt="{{ __('field_photo') }}"
                                            onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                    @else
                                        <img src="{{ asset('dashboard/images/user/avatar-2.jpg') }}"
                                            class="img-radius img-fluid wid-80" alt="{{ __('field_photo') }}">
                                    @endif
                                    <div class="certificated-badge">
                                        <i class="fas fa-certificate text-primary bg-icon"></i>
                                        <i class="fas fa-check front-icon text-white"></i>
                                    </div>
                                </div>
                                <div class="media-body ms-3">
                                    <h6 class="mb-1">{{ $row->first_name }} {{ $row->last_name }}</h6>
                                    @if (isset($row->student_id))
                                        <p class="mb-0 text-muted">#{{ $row->student_id }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <span class="f-w-500"><i class="far fa-envelope m-r-10"></i>{{ __('field_email') }} :
                                </span>
                                <span class="float-end">{{ $row->email }}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="f-w-500"><i class="fas fa-phone-alt m-r-10"></i>{{ __('field_phone') }} :
                                </span>
                                <span class="float-end">{{ $row->phone }}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="f-w-500"><i class="fas fa-users m-r-10"></i>{{ __('Matric No.') }} : </span>
                                <span class="float-end">{{ $row->student_id }}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="f-w-500"><i class="fas fa-graduation-cap m-r-10"></i>{{ __('field_program') }}
                                    : </span>
                                <span class="float-end">{{ $row->program->title ?? '' }}</span>
                            </li>
                            <li class="list-group-item">
                                <span class="f-w-500"><i
                                        class="far fa-calendar-alt m-r-10"></i>{{ __('field_admission_date') }} : </span>
                                <span class="float-end">
                                    @if (isset($setting->date_format))
                                        {{ date($setting->date_format, strtotime($row->admission_date)) }}
                                    @else
                                        {{ date('Y-m-d', strtotime($row->admission_date)) }}
                                    @endif
                                </span>
                            </li>
                            @if (isset($row->registration_no))
                                <li class="list-group-item border-bottom-0">
                                    <span class="f-w-500"><i
                                            class="far fa-question-circle m-r-10"></i>{{ __('field_registration_no') }} :
                                    </span>
                                    <span class="float-end">#{{ $row->registration_no }}</span>
                                </li>
                            @endif
                        </ul>

                        @php
                            $total_credits = 0;
                            $total_cgpa = 0;
                        @endphp
                        @foreach ($row->studentEnrolls as $key => $item)
                            @if (isset($item->subjectMarks))
                                @foreach ($item->subjectMarks as $mark)
                                    @php
                                        $marks_per = round($mark->total_marks);
                                    @endphp

                                    @foreach ($grades as $grade)
                                        @if ($marks_per >= $grade->min_mark && $marks_per <= $grade->max_mark)
                                            @php
                                                if ($grade->point > 0) {
                                                    $total_cgpa = $total_cgpa + $grade->point * $mark->subject->credit_hour;
                                                    $total_credits = $total_credits + $mark->subject->credit_hour;
                                                }
                                            @endphp
                                        @break
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    @endforeach
                    {{-- <div class="card-body">
                        <div class="row text-center">
                            <div class="col">
                                <h6 class="mb-1">{{ number_format((float) $total_credits, 2, '.', '') }}</h6>
                                <p class="mb-0">{{ __('field_total_credit_hour') }}</p>
                            </div>
                            <div class="col border-start">
                                <h6 class="mb-1">
                                    @php
                                        if ($total_credits <= 0) {
                                            $total_credits = 1;
                                        }
                                        $com_gpa = $total_cgpa / $total_credits;
                                        echo number_format((float) $com_gpa, 2, '.', '');
                                    @endphp
                                </h6>
                                <p class="mb-0">{{ __('field_cumulative_gpa') }}</p>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>

            @php
                function field($slug)
                {
                    return \App\Models\Field::field($slug);
                }
            @endphp
            <div class="col-md-8">
                <div class="card">
                    <div class="card-block">
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <fieldset class="row gx-2 scheduler-border">
                                        @if (field('student_father_name')->status == 1)
                                            <p><mark class="text-primary">{{ __('field_father_name') }}:</mark>
                                                {{ $row->father_name }}</p>
                                            <hr />
                                        @endif

                                        <p><mark class="text-primary">{{ __('field_gender') }}:</mark>
                                            @if ($row->gender == 1)
                                                {{ __('gender_male') }}
                                            @elseif($row->gender == 2)
                                                {{ __('gender_female') }}
                                            @elseif($row->gender == 3)
                                                {{ __('gender_other') }}
                                            @endif
                                        </p>
                                        <hr />

                                        <p><mark class="text-primary">{{ __('field_dob') }}:</mark>
                                            @if (isset($setting->date_format))
                                                {{ date($setting->date_format, strtotime($row->dob)) }}
                                            @else
                                                {{ date('Y-m-d', strtotime($row->dob)) }}
                                            @endif
                                        </p>
                                        <hr />
                                        <p><mark class="text-primary">{{ __('field_program') }}:</mark>
                                            {{ $row->program->title ?? '' }}</p>
                                        <hr />
                                        <p><mark class="text-primary">{{ __('field_session') }}:</mark>
                                            {{ $curr_enroll->session->title ?? '' }}</p>
                                        <hr />

                                    </fieldset>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="pills-book-tab" data-bs-toggle="pill" href="#pills-book"
                                    role="tab" aria-controls="pills-book"
                                    aria-selected="false">{{ __('Student Clearance') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-documents-tab" data-bs-toggle="pill"
                                    href="#pills-documents" role="tab" aria-controls="pills-documents"
                                    aria-selected="false">Clearance Documents</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="pills-fees-tab" data-bs-toggle="pill" href="#pills-fees"
                                    role="tab" aria-controls="pills-fees"
                                    aria-selected="false">{{ __('tab_fees_assign') }}</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">

                            <div class="tab-pane fade show active" id="pills-book" role="tabpanel"
                                aria-labelledby="pills-book-tab">
                                <!-- [ Data table ] start -->
                                <div class="table-responsive">
                                    <table id="basic-table2" class="display table nowrap table-striped table-hover"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>{{ __('Admission Letter') }}</th>
                                                <th>{{ __('Birth Certificate') }}</th>
                                                <th>{{ __('First Sitting') }}</th>
                                                <th>{{ __('Second Sitting') }}</th>
                                                <th>{{ __('School Fees') }}</th>
                                                <th>{{ __('Bio Data') }}</th>
                                                <th>{{ __('field_status') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>

                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td class="text-center">
                                                    @if ($clr->admission_letter != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($clr->birth_cert != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($clr->first_sitting_cert != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($clr->decond_sitting_cert != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($clr->school_fees_receipt != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @if ($clr->bio_data != '')
                                                        <span class="text-success"><i class="fas fa-check"></i></span>
                                                    @else
                                                        <span class="text-danger"><i class="fas fa-times"></i></span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($clr->status == 'rejected')
                                                        <span
                                                            class="badge badge-pill badge-danger">{{ __('Rejected') }}</span>
                                                    @elseif($clr->status == 'pending')
                                                        <span
                                                            class="badge badge-pill badge-primary">{{ __('Pending') }}</span>
                                                    @elseif($clr->status == 'processing')
                                                        <span
                                                            class="badge badge-pill badge-secondary">{{ __('Processing') }}</span>
                                                    @elseif($clr->status == 'cleared')
                                                        <span
                                                            class="badge badge-pill badge-success">{{ __('Cleared') }}</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <!-- [ Data table ] end -->
                            </div>
                            <div class="tab-pane fade" id="pills-documents" role="tabpanel"
                                aria-labelledby="pills-documents-tab">
                                <!-- [ Data table ] start -->
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('field_title') }}</th>
                                                <th>{{ __('field_document') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($clr->admission_letter != '')
                                                <tr>
                                                    <td>{{ __('Admission Letter') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->admission_letter) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->birth_cert != '')
                                                <tr>
                                                    <td>{{ __('Birth Certificate') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->birth_cert) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->first_sitting_cert != '')
                                                <tr>
                                                    <td>{{ __('1st Sitting Certificate') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->first_sitting_cert) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->second_sitting_cert != '')
                                                <tr>
                                                    <td>{{ __('2nd Sitting Certificate') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->second_sitting_cert) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->school_fees_receipt != '')
                                                <tr>
                                                    <td>{{ __('School Fees Receipt') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->school_fees_receipt) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->bio_data != '')
                                                <tr>
                                                    <td>{{ __('Bio Data') }}</td>
                                                    <td>
                                                        {{-- @if (is_file('uploads/' . $path . '/' . $student->photo))
                                                                <img src="{{ asset('uploads/' . $path . '/' . $student->photo) }}"
                                                                    class="img-fluid field-image"
                                                                    onerror="this.src='{{ asset('dashboard/images/user/avatar-2.jpg') }}';">
                                                            @endif --}}
                                                        <a
                                                            href="{{ asset('/student_clearance_uploads/' . $clr->bio_data) }}">View
                                                            Document</a>
                                                    </td>
                                                </tr>
                                            @endif
                                            @if ($clr->status == 'processing')
                                                <tr>
                                                    <td></td>
                                                    <td>
                                                        <button type="button" class="btn btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#payModal-{{ $row->id }}">
                                                            Clear/Reject Student
                                                        </button>
                                                        <!-- Include Pay modal -->
                                                        @include($view . '.update')
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                                <!-- [ Data table ] end -->
                            </div>
                            <div class="tab-pane fade" id="pills-fees" role="tabpanel"
                                aria-labelledby="pills-fees-tab">
                                <!-- [ Data table ] start -->
                                @isset($fees)
                                    <div class="table-responsive">
                                        <table id="basic-table" class="display table nowrap table-striped table-hover"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>{{ __('field_session') }}</th>
                                                    <th>{{ __('field_semester') }}</th>
                                                    <th>{{ __('field_fees_type') }}</th>
                                                    <th>{{ __('field_fee') }}</th>
                                                    <th>{{ __('field_discount') }}</th>
                                                    <th>{{ __('field_fine_amount') }}</th>
                                                    <th>{{ __('field_net_amount') }}</th>
                                                    <th>{{ __('field_due_date') }}</th>
                                                    <th>{{ __('field_status') }}</th>
                                                    <th>{{ __('field_pay_date') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($fees->sortByDesc('id') as $key => $row)
                                                    @if ($row->status == 0)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $row->studentEnroll->session->title ?? '' }}</td>
                                                            <td>{{ $row->studentEnroll->semester->title ?? '' }}</td>
                                                            <td>{{ $row->category->title ?? '' }}</td>
                                                            <td>
                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $row->fee_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $row->fee_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $discount_amount = 0;
                                                                    $today = date('Y-m-d');
                                                                @endphp

                                                                @isset($row->category)
                                                                    @foreach ($row->category->discounts->where('status', '1') as $discount)
                                                                        @php
                                                                            $availability = \App\Models\FeesDiscount::availability($discount->id, $row->studentEnroll->student_id);
                                                                        @endphp

                                                                        @if (isset($availability))
                                                                            @if ($discount->start_date <= $today && $discount->end_date >= $today)
                                                                                @if ($discount->type == '1')
                                                                                    @php
                                                                                        $discount_amount = $discount_amount + $discount->amount;
                                                                                    @endphp
                                                                                @else
                                                                                    @php
                                                                                        $discount_amount = $discount_amount + ($row->fee_amount / 100) * $discount->amount;
                                                                                    @endphp
                                                                                @endif
                                                                            @endif
                                                                        @endif
                                                                    @endforeach
                                                                @endisset


                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $discount_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $discount_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $fine_amount = 0;
                                                                @endphp
                                                                @if (empty($row->pay_date) || $row->due_date < $row->pay_date)
                                                                    @php
                                                                        $due_date = strtotime($row->due_date);
                                                                        $today = strtotime(date('Y-m-d'));
                                                                        $days = (int) (($today - $due_date) / 86400);
                                                                    @endphp

                                                                    @if ($row->due_date < date('Y-m-d'))
                                                                        @isset($row->category)
                                                                            @foreach ($row->category->fines->where('status', '1') as $fine)
                                                                                @if ($fine->start_day <= $days && $fine->end_day >= $days)
                                                                                    @if ($fine->type == '1')
                                                                                        @php
                                                                                            $fine_amount = $fine_amount + $fine->amount;
                                                                                        @endphp
                                                                                    @else
                                                                                        @php
                                                                                            $fine_amount = $fine_amount + ($row->fee_amount / 100) * $fine->amount;
                                                                                        @endphp
                                                                                    @endif
                                                                                @endif
                                                                            @endforeach
                                                                        @endisset
                                                                    @endif
                                                                @endif


                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $fine_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $fine_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @php
                                                                    $net_amount = $row->fee_amount - $discount_amount + $fine_amount;
                                                                @endphp

                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $net_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $net_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->date_format))
                                                                    {{ date($setting->date_format, strtotime($row->due_date)) }}
                                                                @else
                                                                    {{ date('Y-m-d', strtotime($row->due_date)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row->status == 1)
                                                                    <span
                                                                        class="badge badge-pill badge-success">{{ __('status_paid') }}</span>
                                                                @elseif($row->status == 2)
                                                                    <span
                                                                        class="badge badge-pill badge-danger">{{ __('status_canceled') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge badge-pill badge-primary">{{ __('status_pending') }}</span>
                                                                @endif
                                                            </td>
                                                            <td></td>
                                                        </tr>
                                                    @elseif($row->status == 1)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $row->studentEnroll->session->title ?? '' }}</td>
                                                            <td>{{ $row->studentEnroll->semester->title ?? '' }}</td>
                                                            <td>{{ $row->category->title ?? '' }}</td>
                                                            <td>
                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $row->fee_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $row->fee_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $row->discount_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $row->discount_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $row->fine_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $row->fine_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->decimal_place))
                                                                    {{ number_format((float) $row->paid_amount, $setting->decimal_place, '.', '') }}
                                                                @else
                                                                    {{ number_format((float) $row->paid_amount, 2, '.', '') }}
                                                                @endif
                                                                {!! $setting->currency_symbol !!}
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->date_format))
                                                                    {{ date($setting->date_format, strtotime($row->due_date)) }}
                                                                @else
                                                                    {{ date('Y-m-d', strtotime($row->due_date)) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($row->status == 1)
                                                                    <span
                                                                        class="badge badge-pill badge-success">{{ __('status_paid') }}</span>
                                                                @elseif($row->status == 2)
                                                                    <span
                                                                        class="badge badge-pill badge-danger">{{ __('status_canceled') }}</span>
                                                                @else
                                                                    <span
                                                                        class="badge badge-pill badge-primary">{{ __('status_pending') }}</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if (isset($setting->date_format))
                                                                    {{ date($setting->date_format, strtotime($row->pay_date)) }}
                                                                @else
                                                                    {{ date('Y-m-d', strtotime($row->pay_date)) }}
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    @endif
                                    <!-- [ Data table ] end -->
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- End Content-->

@endsection

@section('page_js')
    <script type="text/javascript">
        "use strict";
        $('#rejectNote').css('display', 'none');
        $('#selectId').on('change', function() {
            var selectVal = $("#selectId option:selected").val();
            if (selectVal == 'rejected') {
                $('#rejectNote').css('display', 'block');
            } else {
                $('#rejectNote').css('display', 'none');
            }
        });
    </script>
@endsection
