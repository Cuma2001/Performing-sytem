{{-- resources/views/utilities/history.blade.php --}}
@extends('layouts.app')

@section('content')
<div style="max-width: 1400px; margin: 0 auto;">
    <div class="card">
        <div class="card-header">
            <h4><i class="fas fa-history"></i> Upload History</h4>
        </div>
        <div class="card-body">
            <div style="overflow-x: auto;">
                <table class="table table-bordered" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Date</th>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Records</th>
                            <th>Success Rate</th>
                            <th>Uploaded By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($history as $upload)
                            <tr>
                                <td>{{ $upload->id }}</td>
                                <td>{{ $upload->created_at->format('Y-m-d H:i:s') }}</td>
                                <td>{{ $upload->original_filename }}</td>
                                <td>
                                    <span style="background: #f39c12; color: #1e2f3f; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 12px; text-transform: uppercase;">
                                        {{ $upload->type }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $upload->status_badge_class }}">
                                        {{ $upload->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @if($upload->success_records > 0 || $upload->failed_records > 0)
                                        {{ $upload->success_records }} ✓ / {{ $upload->failed_records }} ✗
                                    @else
                                        {{ $upload->total_records }}
                                    @endif
                                </td>
                                <td>
                                    @if($upload->total_records > 0)
                                        <span style="color: {{ $upload->success_rate >= 80 ? 'green' : ($upload->success_rate >= 50 ? 'orange' : 'red') }}">
                                            {{ $upload->formatted_success_rate }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $upload->uploadedBy?->name ?? 'System' }}</td>
                                <td>
                                    <a href="{{ route('utility.history.show', $upload->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    @if($upload->isFailed())
                                        <button onclick="retryUpload({{ $upload->id }})" class="btn btn-sm btn-warning">
                                            <i class="fas fa-redo"></i>
                                        </button>
                                    @endif
                                    <button onclick="deleteUpload({{ $upload->id }})" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" style="text-align: center;">No uploads found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $history->links() }}
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
                    location.reload();
                } else {
                    alert('Failed to delete: ' + response.message);
                }
            }
        });
    }
}
</script>
@endsection