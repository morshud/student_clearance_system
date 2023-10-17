@extends('admin.layouts.master')
@section('title', $title)
@section('content')

    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ $title }}</h5>
                        </div>
                        <div class="card-block">
                            <form class="needs-validation" novalidate method="get" action="{{ route($route . '.index') }}">
                                <div class="row gx-2">
                                    @include('common.inc.student_search_filter')

                                    <div class="form-group col-md-3">
                                        <label for="date">{{ __('field_date') }} <span>*</span></label>
                                        <input type="date" class="form-control date" name="date"
                                            value="{{ $selected_date }}" required>

                                        <div class="invalid-feedback">
                                            {{ __('required_field') }} {{ __('field_date') }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button type="submit" class="btn btn-info btn-filter"><i class="fas fa-search"></i>
                                            {{ __('btn_search') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-block">
                            <!-- [ Data table ] start -->
                            <div class="table-responsive">
                                <table id="export-table" class="display table nowrap table-striped table-hover"
                                    style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('Matric No.') }}</th>
                                            <th>{{ __('field_name') }}</th>
                                            <th>{{ __('field_program') }}</th>
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
                                        @foreach ($rows as $key => $row)
                                            @php
                                                $clr = \App\Models\StudentClearance::where('student_id', $row->id)->first();
                                            @endphp
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>
                                                    <a href="{{ route($route . '.show', $row->id) }}">
                                                        {{ $row->student_id }}
                                                    </a>
                                                </td>
                                                <td>{{ $row->first_name }} {{ $row->last_name }}</td>
                                                <td>{{ $row->program->shortcode ?? '' }}</td>
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
                                                {{-- <td>
                                                @can($access . '-edit')
                                                @if ($row->login == 1)
                                                <a href="{{ route($route.'.status', $row->id) }}" class="btn btn-icon btn-danger btn-sm"><i class="fas fa-times"></i></a>
                                                @else
                                                <a href="{{ route($route.'.status', $row->id) }}" class="btn btn-icon btn-success btn-sm"><i class="fas fa-check"></i></a>
                                                @endif
                                                @else
                                                @if ($row->login == 1)
                                                <span class="badge badge-pill badge-success">{{ __('status_active') }}</span>
                                                @else
                                                <span class="badge badge-pill badge-danger">{{ __('status_blocked') }}</span>
                                                @endif
                                                @endcan
                                            </td> --}}
                                                <td>

                                                    @if ($clr->status == 'processing')
                                                        <button type="button" class="btn btn-icon btn-primary btn-sm"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#payModal-{{ $row->id }}">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    @endif
                                                    <!-- Include Pay modal -->
                                                    @include($view . '.update')
                                                    <a href="{{ route($route . '.show', $row->id) }}"
                                                        class="btn btn-icon btn-success btn-sm">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
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
