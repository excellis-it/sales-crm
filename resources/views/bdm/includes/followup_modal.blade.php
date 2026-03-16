    <!-- Follow-up Modal -->
    <div id="followup_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content overflow-hidden" style="border-radius: 15px; border: none;">
                <div class="modal-header" style="background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); padding: 20px;">
                    <h5 class="modal-title text-white" style="font-weight: 600;"><i class="fas fa-comments me-2"></i> Remarks & Follow-ups History</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close" style="opacity: 1; border: none; background: transparent; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0">
                    <div id="followup_content" style="background: #fdfdfd;">
                        <!-- Content loaded by AJAX -->
                    </div>
                    <div class="p-4 bg-light border-top">
                        <form id="add_followup_form">
                            @csrf
                            <input type="hidden" name="id" id="followup_item_id">
                            <input type="hidden" name="type" id="followup_item_type"> <!-- 'project' or 'prospect' -->
                            <input type="hidden" name="prefix" id="followup_prefix">

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold"><i class="fas fa-pencil-alt me-1"></i> Add New Remark <span class="text-danger">*</span></label>
                                <textarea name="comment" class="form-control border-0 shadow-sm" rows="3" required placeholder="Type your follow-up note here..." style="border-radius: 10px; resize: none;"></textarea>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label fw-bold"><i class="far fa-calendar-alt me-1"></i> Next Follow-up Date (Optional)</label>
                                <input type="date" name="next_followup_date" class="form-control border-0 shadow-sm" style="border-radius: 10px;">
                            </div>

                            <div class="submit-section text-center mb-2">
                                <button type="submit" class="btn btn-primary px-5 py-2" style="background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); border: none; border-radius: 25px;">Submit Follow-up</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /Follow-up Modal -->


 <script>
        $(document).ready(function() {
            // Mapping of named routes by prefix and type.
            // Using a '__ID__' placeholder ensures Laravel's route helper won't throw missing parameter exceptions,
            // and allows us to simply .replace('__ID__', id) in Javascript.
            var followupRoutes = {
                'bde': {
                    'projects': {
                        get: "{{ route('bde.bde-projects.followups', '__ID__') }}",
                        add: "{{ route('bde.bde-projects.add-followup') }}"
                    },
                    'prospects': {
                        get: "{{ route('bde.bde-prospects.followups', '__ID__') }}",
                        add: "{{ route('bde.bde-prospects.add-followup') }}"
                    }
                },
                'bdm': {
                    'projects': {
                        get: "{{ route('bdm.projects.followups', '__ID__') }}",
                        add: "{{ route('bdm.projects.add-followup') }}"
                    },
                    'prospects': {
                        get: "{{ route('bdm.prospects.followups', '__ID__') }}",
                        add: "{{ route('bdm.prospects.add-followup') }}"
                    }
                },
                'admin': {
                    'projects': {
                        get: "{{ route('admin.bdm-projects.followups', '__ID__') }}",
                        add: "{{ route('admin.bdm-projects.add-followup') }}"
                    },
                    'prospects': {
                        get: "{{ route('admin.bdm-prospects.followups', '__ID__') }}",
                        add: "{{ route('admin.bdm-prospects.add-followup') }}"
                    }
                }
            };

            // View Follow-ups
            $(document).on('click', '.view-followups', function() {
                var id = $(this).data('id');

                // Determine if project or prospect based on page URL
                var type = window.location.href.includes('prospects') ? 'prospects' : 'projects';

                var prefix = window.location.href.includes('/bde/') ? 'bde' :
                    window.location.href.includes('/bdm/') ? 'bdm' : 'admin';

                var routeData = followupRoutes[prefix][type];
                var get_url = routeData.get.replace('__ID__', id);

                $('#followup_item_id').val(id);
                $('#followup_item_type').val(type);

                // Temporarily store dynamic urls for the form submission
                $('#followup_prefix').val(get_url);
                $('#followup_item_id').attr('data-add-url', routeData.add);

                loadFollowups(get_url);
                $('#followup_modal').modal('show');
            });

            function loadFollowups(url) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(response) {
                        $('#followup_content').html(response.view);
                    }
                });
            }

            // Add Follow-up
            $('#add_followup_form').submit(function(e) {
                e.preventDefault();
                var id = $('#followup_item_id').val();
                var type = $('#followup_item_type').val();
                var get_url = $('#followup_prefix').val();
                var add_url = $('#followup_item_id').attr('data-add-url');

                var formData = $(this).serializeArray();

                // Add correct ID parameter based on type
                if (type === 'projects') {
                    formData.push({
                        name: 'bdm_project_id',
                        value: id
                    });
                } else {
                    formData.push({
                        name: 'bdm_prospect_id',
                        value: id
                    });
                }

                $.ajax({
                    url: add_url,
                    type: 'POST',
                    data: $.param(formData),
                    success: function(response) {
                        if (response.success) {
                            if (typeof toastr !== 'undefined') {
                                toastr.success(response.success);
                            } else {
                                alert(response.success);
                            }
                            $('#add_followup_form')[0].reset();
                            loadFollowups(get_url);
                        }
                    },
                    error: function(xhr) {
                        var err = typeof xhr.responseJSON !== 'undefined' && xhr.responseJSON
                            .message ? xhr.responseJSON.message : 'Something went wrong';
                        if (typeof toastr !== 'undefined') {
                            toastr.error(err);
                        } else {
                            alert(err);
                        }
                    }
                });
            });
        });
    </script>
