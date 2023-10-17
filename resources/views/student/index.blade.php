@extends('student.layouts.master')
@section('title', $title)

@section('page_css')
    <!-- Full calendar css -->
    <link rel="stylesheet" href="{{ asset('dashboard/plugins/fullcalendar/css/fullcalendar.min.css') }}">
@endsection

@section('content')

    <!-- Start Content-->
    <div class="main-body">
        <div class="page-wrapper">
            <!-- [ Main Content ] start -->
            <div class="row">

                @php
                    function field($slug)
                    {
                        return \App\Models\Field::field($slug);
                    }
                    $student_id = Auth::guard('student')->user()->id;
                    $clr = \App\Models\StudentClearance::where('student_id', $student_id)->first();
                @endphp

                @if ($clr->status == 'pending')
                    <div class="alert alert-warning">
                        You have a pending clearance to upload, please navigate to clearance page to upload your clearance
                        documents...
                    </div>
                @endif

                @if ($clr->status == 'rejected')
                    <div class="alert alert-danger">
                        Your clearance document has been rejected, navigate to clearance page to check the reason and
                        re-upload...
                    </div>
                @endif

                @if (field('panel_fees_report')->status == 1)
                    <div class="col-sm-12 col-lg-12">
                        <div class="card">
                            @if (isset($fees))
                                <div class="card-header">
                                    <h5>{{ trans_choice('module_student_fees', 2) }}</h5>
                                </div>
                                <div class="card-block">
                                    <!-- [ Data table ] start -->
                                    <div class="table-responsive">
                                        <table class="display table nowrap table-striped table-hover" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('field_fees_type') }}</th>
                                                    <th>{{ __('field_fee') }}</th>
                                                    <th>{{ __('field_status') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($fees as $key => $row)
                                                    <tr>
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
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- [ Data table ] end -->
                                </div>
                            @endif
                        </div>
                    </div>
                @endif

            </div>

            <div class="row">
                <div class="col-xl-12 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header">
                            <h5>{{ trans_choice('module_calendar', 2) }}</h5>
                        </div>
                        <div class="card-block">

                            <!-- [ Calendar ] start -->
                            <div id='calendar' class='calendar'></div>
                            <!-- [ Calendar ] end -->

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
    <!-- Full calendar js -->
    <script src="{{ asset('dashboard/plugins/fullcalendar/js/lib/moment.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/fullcalendar/js/lib/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('dashboard/plugins/fullcalendar/js/fullcalendar.min.js') }}"></script>


    <script type="text/javascript">
        // Full calendar
        $(window).on('load', function() {
            "use strict";
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next',
                    center: 'title',
                    right: 'today'
                },
                defaultDate: '@php echo date("Y-m-d"); @endphp',
                editable: false,
                droppable: false,
                events: [

                    @php
                        foreach ($events as $key => $row) {
                            echo "{
                                title: '" .
                                $row->title .
                                "',
                                start: '" .
                                $row->start_date .
                                "',
                                end: '" .
                                $row->end_date .
                                "',
                                borderColor: '" .
                                $row->color .
                                "',
                                backgroundColor: '" .
                                $row->color .
                                "',
                                textColor: '#fff'
                            }, ";
                        }
                    @endphp

                ],
            });
        });
    </script>
@endsection
