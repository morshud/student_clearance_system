    <!-- Edit modal content -->
    <div id="payModal-{{ $row->id }}" class="modal fade" tabindex="-1" role="dialog" id="payModal-{{ $row->id }}"
        aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <form class="needs-validation" novalidate action="{{ route('admin.clearance.update', $clr->id) }}"
                    method="get" enctype="multipart/form-data">
                    @method('PUT')
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
                                    <p><mark class="text-primary">{{ __('field_student_id') }}:</mark>
                                        #{{ $row->student_id ?? '' }}</p>
                                    <hr />
                                    <p><mark class="text-primary">{{ __('field_name') }}:</mark>
                                        {{ $row->first_name ?? '' }}
                                        {{ $row->last_name ?? '' }}</p>
                                    <hr />
                                    <p><mark class="text-primary">{{ __('field_program') }}:</mark>
                                        {{ \App\Models\Student::enroll($row->id)->program->title ?? '' }}</p>
                                    <hr />
                                </div>
                            </div>
                        </div>
                        <br />

                        <input type="text" name="student_id" value="{{ $row->id }}" hidden>

                        <!-- Form Start -->
                        <div class="row">
                            <div class="form-group col-md-12">
                                <label for="payment_method" class="form-label">{{ __('field_status') }}
                                    <span>*</span></label>
                                <select class="form-control" id="selectId" name="status"
                                    id="payment_method-{{ $row->id }}" required>
                                    <option value="">{{ __('select') }}</option>
                                    <option value="cleared" @if (old('status') == 'cleared') selected @endif>
                                        {{ __('Clear Student') }}</option>
                                    <option value="rejected" @if (old('status') == 'rejected') selected @endif>
                                        {{ __('Reject Clearance') }}</option>
                                </select>

                                <div class="invalid-feedback">
                                    {{ __('required_field') }} {{ __('field_status') }}
                                </div>
                            </div>

                            <div class="form-group col-md-12" id="rejectNote">
                                <label for="note" class="form-label">{{ __('Reject Reason') }}</label>
                                <textarea name="reject_note" class="form-control" id="" rows="3"></textarea>
                            </div>
                        </div>
                        <!-- Form End -->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i
                                class="fas fa-times"></i> {{ __('btn_close') }}</button>
                        <button type="submit" class="btn btn-success"><i class="fas fa-money-check"></i>
                            {{ __('update') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
