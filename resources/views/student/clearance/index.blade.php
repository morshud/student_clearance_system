@extends('student.layouts.master')
@section('title', $title)
@section('content')
    @php
        $clr = \App\Models\StudentClearance::where('student_id', $user->id)->first();
    @endphp
    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $title }}</h5>
                        </div>
                        <div class="card-block">
                            <form class="needs-validation" novalidate method="get" action="{{ route($route . '.index') }}">
                                <div class="row gx-2">
                                    <div class="form-group col-md-3">
                                        <label for="session">{{ __('field_session') }}</label>
                                        <select class="form-control" name="session" id="session">
                                            <option value="0">{{ __('all') }}</option>
                                            @foreach ($sessions as $session)
                                                <option value="{{ $session->session_id }}"
                                                    @if ($selected_session == $session->session_id) selected @endif>
                                                    {{ $session->session->title }}</option>
                                            @endforeach
                                        </select>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_session') }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <label for="semester">{{ __('field_semester') }}</label>
                                        <select class="form-control" name="semester" id="semester">
                                            <option value="0">{{ __('all') }}</option>
                                            @foreach ($semesters as $semester)
                                                <option value="{{ $semester->semester_id }}"
                                                    @if ($selected_semester == $semester->semester_id) selected @endif>
                                                    {{ $semester->semester->title }}</option>
                                            @endforeach
                                        </select>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_semester') }}
                                        </div>
                                    </div>

                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-info btn-filter"><i class="fas fa-search"></i>
                                            {{ __('btn_filter') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="card-block">
                            <!-- [ Data table ] start -->

                            <div class="table-responsive">
                                <table id="basic-table" class="display table nowrap table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('field_session') }}</th>
                                            <th>{{ __('Admission Letter') }}</th>
                                            <th>{{ __('Birth Certificate') }}</th>
                                            <th>{{ __('First Sitting') }}</th>
                                            <th>{{ __('Second Sitting') }}</th>
                                            <th>{{ __('School Fees') }}</th>
                                            <th>{{ __('Biodata') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('field_action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                        <tr>
                                            <td>1</td>
                                            <td>{{ \App\Models\Student::enroll($user->id)->session->title ?? '' }}</td>
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
                                                    <span class="badge badge-pill badge-danger">{{ __('Rejected') }}</span>
                                                @elseif($clr->status == 'pending')
                                                    <span class="badge badge-pill badge-warning">{{ __('Pending') }}</span>
                                                @elseif($clr->status == 'processing')
                                                    <span
                                                        class="badge badge-pill badge-secondary">{{ __('Processing') }}</span>
                                                @elseif($clr->status == 'cleared')
                                                    <span class="badge badge-pill badge-success">{{ __('Cleared') }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($clr->status == 'pending')
                                                    <button type="button" class="btn btn-icon btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#payModal-{{ $clr->id }}">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                @endif
                                                <!-- Include Pay modal -->
                                                @include($view . '.upload')
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- [ Data table ] end -->
                        </div>

                    </div>
                </div>
            </div>
            <!-- [ Main Content ] end -->
        </div>
    </div>
    <!-- End Content-->
    @if ($clr->status == 'rejected')
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5>Clearance Reject Reason</h5>
                </div>
                <div class="card-body">
                    <p>{{ $clr->reject_reason }}</p>
                </div>
            </div>
        </div>
    @endif


@endsection
