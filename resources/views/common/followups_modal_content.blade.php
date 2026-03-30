<div style="background: #fdfdfd;">
<div class="timeline-container">
    @if(isset($followups) && count($followups) > 0)
        @foreach($followups as $followup)
            <div class="timeline-entry">
                <div class="timeline-marker remark-bg">
                    <i class="fas fa-comment-alt"></i>
                </div>
                <div class="timeline-card">
                    <div class="timeline-card-header">
                        <div class="user-info">
                            <span class="user-name">{{ $followup->user->name ?? 'N/A' }}</span>
                            <span class="type-badge remark">
                                {{ strtoupper($followup->followup_type ?? 'REMARK') }}
                            </span>
                            @if ($followup->status)
                                <span class="badge {{ $followup->status == 'Win' ? 'bg-success' : ($followup->status == 'Follow Up' ? 'bg-warning' : ($followup->status == 'In Meeting' ? 'bg-info' : ($followup->status == 'Sent Proposal' ? 'bg-primary' : 'bg-secondary'))) }}"
                                    style="font-size: 10px; margin-left: 5px;">
                                    {{ $followup->status }}
                                </span>
                            @endif
                        </div>
                        <span class="time-stamp">
                            <i class="far fa-clock"></i> {{ $followup->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                    <div class="timeline-card-body">
                        <p>{{ $followup->followup_description }}</p>
                        <div class="mt-2 d-flex flex-wrap gap-2">
                            @if($followup->last_call_status)
                                <span style="font-size: 11px; color: #dc3545; background: #fde8ea; padding: 2px 8px; border-radius: 4px;">
                                    <i class="fas fa-phone-slash me-1"></i> Last Call: {{ $followup->last_call_status }}
                                </span>
                            @endif
                            @if($followup->next_followup_date)
                                <span style="font-size: 11px; color: #fd7e14; background: #fff4e6; padding: 2px 8px; border-radius: 4px;">
                                    <i class="far fa-calendar-alt me-1"></i> Next Follow-up: {{ \Carbon\Carbon::parse($followup->next_followup_date)->format('d M Y') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>No remarks or follow-ups found.</p>
        </div>
    @endif
</div>
</div>
 @if ($project == true)
     <div class="p-4 bg-light border-top">
         <form id="add_followup_form">
             @csrf
             <input type="hidden" name="id" id="followup_item_id" value="{{ $id }}"
                 data-add-url="{{ $add_url }}" data-id-field="{{ $id_field }}">
             <input type="hidden" name="type" id="followup_item_type" value="{{ $type }}"
                 data-add-url="{{ $add_url }}" data-id-field="{{ $id_field }}">
             <!-- 'project' or 'prospect' -->
             <input type="hidden" name="prefix" id="followup_prefix" value="{{ $prefix }}">

            
             <div class="form-group mb-3">
                 <label class="form-label fw-bold"><i class="fas fa-pencil-alt me-1"></i> Add New Remark <span
                         class="text-danger">*</span></label>
                 <textarea name="comment" class="form-control border-0 shadow-sm" rows="3" required
                     placeholder="Type your follow-up note here..." style="border-radius: 10px; resize: none;"></textarea>
             </div>

             <div class="form-group mb-3">
                 <label class="form-label fw-bold"><i class="fas fa-phone-alt me-1"></i> Last Call Status (Optional)</label>
                 <select name="last_call_status" class="form-control border-0 shadow-sm" style="border-radius: 10px;">
                     <option value="">-- Select --</option>
                     <option value="Connected">Connected</option>
                     <option value="Not Connected">Not Connected</option>
                     <option value="Busy">Busy</option>
                     <option value="No Answer">No Answer</option>
                     <option value="Switched Off">Switched Off</option>
                     <option value="Wrong Number">Wrong Number</option>
                 </select>
             </div>

             <div class="submit-section text-center mb-2">
                 <button type="submit" class="btn btn-primary px-5 py-2"
                     style="background: linear-gradient(135deg, #ff9b44 0%, #fc6075 100%); border: none; border-radius: 25px;">Submit
                     Follow-up</button>
             </div>
         </form>
     </div>
 @endif
<style>
    .timeline-container {
        position: relative;
        padding: 20px 10px;
        max-height: 400px;
        overflow-y: auto;
    }
    .timeline-container::before {
        content: '';
        position: absolute;
        left: 28px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: #e9ecef;
    }
    .timeline-entry {
        position: relative;
        padding-left: 55px;
        margin-bottom: 25px;
    }
    .timeline-marker {
        position: absolute;
        left: 17px;
        top: 0;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1;
        box-shadow: 0 0 0 4px #fff;
    }
    .timeline-marker i {
        font-size: 10px;
        color: #fff;
    }
    .remark-bg { background: #ff9b44; }
    
    .timeline-card {
        background: #fff;
        border-radius: 8px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        transition: all 0.3s ease;
    }
    .timeline-card:hover {
        transform: translateX(5px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }
    .timeline-card-header {
        padding: 10px 15px;
        border-bottom: 1px solid #f8f9fa;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
        border-radius: 8px 8px 0 0;
    }
    .user-name {
        font-weight: 700;
        font-size: 13px;
        color: #333;
        margin-right: 10px;
    }
    .type-badge {
        font-size: 10px;
        padding: 2px 8px;
        border-radius: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }
    .type-badge.remark { background: #fff2e6; color: #ff9b44; }
    
    .time-stamp {
        font-size: 11px;
        color: #999;
    }
    .timeline-card-body {
        padding: 12px 15px;
    }
    .timeline-card-body p {
        margin: 0;
        font-size: 13px;
        line-height: 1.6;
        color: #555;
    }
    .empty-state {
        text-align: center;
        padding: 40px;
        color: #adb5bd;
    }
    .empty-state i {
        font-size: 40px;
        margin-bottom: 15px;
        display: block;
    }
</style>
