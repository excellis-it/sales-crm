<div class="timeline-container">
    @if(isset($followups) && count($followups) > 0)
        @foreach($followups as $followup)
            <div class="timeline-entry">
                <div class="timeline-marker {{ $followup->type == 'Remark' ? 'remark-bg' : 'milestone-bg' }}">
                    <i class="fas {{ $followup->type == 'Remark' ? 'fa-comment-alt' : 'fa-flag-checkered' }}"></i>
                </div>
                <div class="timeline-card">
                    <div class="timeline-card-header">
                        <div class="user-info">
                            <span class="user-name">{{ $followup->user->name ?? 'N/A' }}</span>
                            <span class="type-badge {{ strtolower(str_replace(' ', '-', $followup->type)) }}">
                                {{ $followup->type }}
                            </span>
                        </div>
                        <span class="time-stamp">
                            <i class="far fa-clock"></i> {{ $followup->created_at->format('d M Y, h:i A') }}
                        </span>
                    </div>
                    <div class="timeline-card-body">
                        <p>{{ $followup->comment }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @else
        <div class="empty-state">
            <i class="fas fa-history"></i>
            <p>No remarks or follow-ups found for this project.</p>
        </div>
    @endif
</div>

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
    .milestone-bg { background: #00d2b5; }
    
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
    .type-badge.milestone-comment { background: #e6fffc; color: #00d2b5; }
    
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
