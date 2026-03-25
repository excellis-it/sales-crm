    <!-- Follow-up Modal -->
    <div id="followup_modal" class="modal custom-modal fade" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content overflow-hidden" style="border-radius: 15px; border: none;">
                <div class="modal-header"
                    style="background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); padding: 20px;">
                    <h5 class="modal-title text-white" style="font-weight: 600;"><i class="fas fa-comments me-2"></i>
                        Remarks & Follow-ups History</h5>
                    <button type="button" class="close text-white" data-bs-dismiss="modal" aria-label="Close"
                        style="opacity: 1; border: none; background: transparent; font-size: 24px;">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" id="followup_content">

                    <!-- Content loaded by AJAX -->


                </div>
            </div>
        </div>
    </div>
    <!-- /Follow-up Modal -->


    <script>
        $(document).ready(function() {
            // Mapping of named routes by prefix and type.
            var followupRoutes = {
                'bde': {
                    'projects': {
                        get: "{{ route('bde.bde-projects.followups', '__ID__') }}",
                        add: "{{ route('bde.bde-projects.add-followup') }}",
                        id_field: 'bdm_project_id'
                    },
                    'prospects': {
                        get: "{{ route('bde.bde-prospects.followups', '__ID__') }}",
                        add: "{{ route('bde.bde-prospects.add-followup') }}",
                        id_field: 'bdm_prospect_id'
                    }
                },
                'bdm': {
                    'projects': {
                        get: "{{ route('bdm.projects.followups', '__ID__') }}",
                        add: "{{ route('bdm.projects.add-followup') }}",
                        id_field: 'bdm_project_id'
                    },
                    'prospects': {
                        get: "{{ route('bdm.prospects.followups', '__ID__') }}",
                        add: "{{ route('bdm.prospects.add-followup') }}",
                        id_field: 'bdm_prospect_id'
                    }
                },
                'admin': {
                    'bdm-projects': {
                        get: "{{ route('admin.bdm-projects.followups', '__ID__') }}",
                        add: "{{ route('admin.bdm-projects.add-followup') }}",
                        id_field: 'bdm_project_id'
                    },
                    'bdm-prospects': {
                        get: "{{ route('admin.bdm-prospects.followups', '__ID__') }}",
                        add: "{{ route('admin.bdm-prospects.add-followup') }}",
                        id_field: 'bdm_prospect_id'
                    },
                    'sales-projects': {
                        get: "{{ route('admin.sales-projects.followups', '__ID__') }}",
                        add: "{{ route('admin.sales-projects.add-followup') }}",
                        id_field: 'project_id'
                    },
                    'prospects': {
                        get: "{{ route('admin.prospects.followups', '__ID__') }}",
                        add: "{{ route('admin.prospects.add-followup') }}",
                        id_field: 'prospect_id'
                    }
                },
                'sales-manager': {
                    'projects': {
                        get: "{{ route('sales-manager.projects.followups', '__ID__') }}",
                        add: "{{ route('sales-manager.projects.add-followup') }}",
                        id_field: 'project_id'
                    },
                    'prospects': {
                        get: "{{ route('sales-manager.prospects.followups', '__ID__') }}",
                        add: "{{ route('sales-manager.prospects.add-followup') }}",
                        id_field: 'prospect_id'
                    }
                },
                'account-manager': {
                    'projects': {
                        get: "{{ route('account-manager.projects.followups', '__ID__') }}",
                        add: "{{ route('account-manager.projects.add-followup') }}",
                        id_field: 'project_id'
                    }
                },
                'sales-excecutive': {
                    'projects': {
                        get: "{{ route('sales-excecutive.projects.followups', '__ID__') }}",
                        add: "{{ route('sales-excecutive.projects.add-followup') }}",
                        id_field: 'project_id'
                    },
                    'prospects': {
                        get: "{{ route('sales-excecutive.prospects.followups', '__ID__') }}",
                        add: "{{ route('sales-excecutive.prospects.add-followup') }}",
                        id_field: 'prospect_id'
                    }
                }
            };

            // View Follow-ups
            $(document).on('click', '.view-followups', function() {
                var id = $(this).data('id');
                var currentUrl = window.location.href;

                // Determine prefix
                var prefix = 'admin';
                if (currentUrl.includes('/bde/')) prefix = 'bde';
                else if (currentUrl.includes('/bdm/')) prefix = 'bdm';
                else if (currentUrl.includes('/sales-manager/')) prefix = 'sales-manager';
                else if (currentUrl.includes('/account-manager/')) prefix = 'account-manager';
                else if (currentUrl.includes('/sales-excecutive/')) prefix = 'sales-excecutive';

                // Determine type
                var type = 'projects';
                if (prefix === 'admin') {
                    if (currentUrl.includes('bdm-prospects')) type = 'bdm-prospects';
                    else if (currentUrl.includes('bdm-projects')) type = 'bdm-projects';
                    else if (currentUrl.includes('prospects')) type = 'prospects';
                    else type = 'sales-projects';
                } else {
                    type = currentUrl.includes('prospect') ? 'prospects' : 'projects';
                }

                var routeData = followupRoutes[prefix][type];
                if (!routeData) return; 

                var get_url = routeData.get.replace('__ID__', id);
                var routeDataAdd =routeData.add;
                var routeDataIdField =routeData.id_field;

                $('#followup_item_id').val(id);
                $('#followup_item_type').val(type);
                $('#followup_prefix').val(get_url);
                $('#followup_item_id').attr('data-add-url', routeDataAdd);
                $('#followup_item_id').attr('data-id-field', routeDataIdField);

                loadFollowups(id, type, get_url, routeDataAdd, routeDataIdField);


                // Show/hide prospect specific fields
                if (type.includes('prospect')) {
                    $('.prospect-only-fields').show();
                } else {
                    $('.prospect-only-fields').hide();
                }

                // Reset fields
                $('#followup_status').val('');
                $('#followup_meeting_date').val('').attr('required', false);
                $('#followup_meeting_date_div').hide();

                $('#followup_modal').modal('show');
            });

            // Toggle meeting date based on status in modal
            $(document).on('change', '#followup_status', function() {
                var status = $(this).val();
                if (status === 'In Meeting') {
                    $('#followup_meeting_date_div').show();
                    $('#followup_meeting_date').attr('required', true);
                } else {
                    $('#followup_meeting_date_div').hide();
                    $('#followup_meeting_date').attr('required', false);
                }
            });

            function loadFollowups(id, type, url, add_url, id_field) {
                $.ajax({
                    url: url,
                    type: 'GET',
                    data: {
                        id: id,
                        type: type,
                        url: url,
                        add_url: add_url,
                        id_field: id_field  
                    },
                    success: function(response) {
                        $('#followup_content').html(response.view);
                    }
                });
            }

            // Add Follow-up
            $(document).on('submit', '#add_followup_form', function(e) {
                e.preventDefault();
                var id = $('#followup_item_id').val();
                var get_url = $('#followup_prefix').val();
                var add_url = $('#followup_item_id').attr('data-add-url');
                var id_field = $('#followup_item_id').attr('data-id-field');
                var type = $('#followup_item_type').val();
                var formData = $(this).serializeArray();
                formData.push({
                    name: id_field,
                    value: id
                });

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
                            loadFollowups(id, type, get_url, add_url, id_field);
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
