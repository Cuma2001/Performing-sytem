{{-- resources/views/utilities/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-file-upload"></i> Upload Details</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">File Name</dt>
                        <dd class="col-sm-8">{{ $upload->original_filename }}</dd>
                        
                        <dt class="col-sm-4">Type</dt>
                        <dd class="col-sm-8">
                            <span style="background: #f39c12; color: #1e2f3f; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; text-transform: uppercase;">
                                {{ $upload->type }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-4">Status</dt>
                        <dd class="col-sm-8">
                            <span class="badge bg-{{ $upload->status_badge_class }}">
                                {{ $upload->status_label }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-4">Uploaded By</dt>
                        <dd class="col-sm-8">{{ $upload->uploadedBy?->name ?? 'System' }}</dd>
                        
                        <dt class="col-sm-4">Uploaded At</dt>
                        <dd class="col-sm-8">{{ $upload->created_at->format('Y-m-d H:i:s') }}</dd>
                        
                        @if($upload->processing_started_at)
                            <dt class="col-sm-4">Processing Started</dt>
                            <dd class="col-sm-8">{{ $upload->processing_started_at->format('Y-m-d H:i:s') }}</dd>
                        @endif
                        
                        @if($upload->processing_completed_at)
                            <dt class="col-sm-4">Processing Completed</dt>
                            <dd class="col-sm-8">{{ $upload->processing_completed_at->format('Y-m-d H:i:s') }}</dd>
                        @endif
                    </dl>
                </div>
                <div class="col-md-6">
                    <dl class="row">
                        <dt class="col-sm-4">Total Records</dt>
                        <dd class="col-sm-8">{{ $upload->total_records }}</dd>
                        
                        <dt class="col-sm-4">Success Records</dt>
                        <dd class="col-sm-8 text-success">{{ $upload->success_records }}</dd>
                        
                        <dt class="col-sm-4">Failed Records</dt>
                        <dd class="col-sm-8 text-danger">{{ $upload->failed_records }}</dd>
                        
                        <dt class="col-sm-4">Success Rate</dt>
                        <dd class="col-sm-8">
                            <span style="color: {{ $upload->success_rate >= 80 ? 'green' : ($upload->success_rate >= 50 ? 'orange' : 'red') }}">
                                {{ $upload->formatted_success_rate }}
                            </span>
                        </dd>
                        
                        <dt class="col-sm-4">File Size</dt>
                        <dd class="col-sm-8">
                            @if($upload->file_path && Storage::disk('public')->exists($upload->file_path))
                                {{ number_format(Storage::disk('public')->size($upload->file_path) / 1024, 2) }} KB
                            @else
                                N/A
                            @endif
                        </dd>
                    </dl>
                </div>
            </div>
            
            @if($upload->error_log && count($upload->error_log) > 0)
                <div class="mt-4">
                    <h5><i class="fas fa-exclamation-triangle text-danger"></i> Error Log</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Message</th>
                                    <th>Context</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upload->error_log as $error)
                                    <tr>
                                        <td>{{ $error['timestamp'] ?? 'N/A' }}</td>
                                        <td>{{ $error['message'] ?? 'N/A' }}</td>
                                        <td>
                                            @if(isset($error['context']))
                                                <pre style="max-height: 100px; overflow: auto;">{{ json_encode($error['context'], JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            @if($upload->validation_errors && count($upload->validation_errors) > 0)
                <div class="mt-4">
                    <h5><i class="fas fa-exclamation-circle text-warning"></i> Validation Errors</h5>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>Row</th>
                                    <th>Field</th>
                                    <th>Message</th>
                                    <th>Timestamp</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($upload->validation_errors as $error)
                                    <tr>
                                        <td>{{ $error['row'] ?? 'N/A' }}</td>
                                        <td>{{ $error['field'] ?? 'N/A' }}</td>
                                        <td>{{ $error['message'] ?? 'N/A' }}</td>
                                        <td>{{ $error['timestamp'] ?? 'N/A' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('utility.history') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to History
                </a>
                
                @if($upload->isFailed())
                    <button onclick="retryUpload({{ $upload->id }})" class="btn btn-warning">
                        <i class="fas fa-redo"></i> Retry Upload
                    </button>
                @endif
                
                <button onclick="deleteUpload({{ $upload->id }})" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Delete
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function retryUpload(id) {
    if (confirm('Are you sure you want to retry this upload?')) {
        $.post('{{ route('utility.retry', '') }}/' + id, {
            _token: '{{ csrf_token() }}'
        }, function(response) {
            if (response.success) {
                alert('Upload retried successfully!');
                location.reload();
            } else {
                alert('Failed to retry: ' + response.message);
            }
        });
    }
}

function deleteUpload(id) {
    if (confirm('Are you sure you want to delete this upload? This action cannot be undone.')) {
        $.ajax({
            url: '{{ route('utility.delete', '') }}/' + id,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    alert('Upload deleted successfully!');
                    window.location.href = '{{ route('utility.history') }}';
                } else {
                    alert('Failed to delete: ' + response.message);
                }
            }
        });
    }
}
</script>
@endsection