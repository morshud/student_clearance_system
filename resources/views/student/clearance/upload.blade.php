    <!-- Edit modal content -->
    <div id="payModal-{{ $clr->id }}" class="modal fade" tabindex="-1" role="dialog" id="payModal-{{ $clr->id }}"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="needs-validation" novalidate action="{{ route('student.clearance.update', $clr->id) }}"
                    method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel">{{ $title }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><span
                                aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <!-- View Start -->
                        <div class="">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><mark class="text-primary">{{ __('Matric No.') }}:</mark>
                                        {{ $user->student_id ?? '' }}</p>
                                    <hr />
                                    <p><mark class="text-primary">{{ __('field_name') }}:</mark>
                                        {{ $user->first_name ?? '' }}
                                        {{ $user->last_name ?? '' }}</p>
                                    <hr />
                                    <p><mark class="text-primary">{{ __('field_program') }}:</mark>
                                        {{ \App\Models\Student::enroll($user->id)->program->title ?? '' }}</p>
                                    <hr />
                                </div>
                            </div>
                        </div>
                        <br />
                        <p><b>Note:</b> Scanned Image file only accepted</p>
                        <input type="text" name="student_id" value="{{ $user->id }}" hidden>

                        <!-- Form Start -->
                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('Admission Letter') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="admission_letter" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('Birth Certificate') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="birth_cert" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('1st Sitting Exam') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="first_sitting" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('2nd Sitting Exam') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="second_sitting" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('School Fees Receipt') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="school_fees_receipt" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_method" class="form-label">{{ __('Bio Data') }}
                                    <span>*</span></label>
                                <input type="file" accept="image/*" name="bio_data" class="form-control"
                                    id="">

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>
                        </div>
                        <!-- Form End -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-times"></i> {{ __('btn_close') }}</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-money-check"></i>
                            {{ __('Upload') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
