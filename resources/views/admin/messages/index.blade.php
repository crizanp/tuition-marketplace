@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Message Management</h1>
        <div class="btn-group">
            <a href="{{ route('admin.messages.export') }}" class="btn btn-success">
                <i class="fas fa-download"></i> Export CSV
            </a>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filter Messages</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.messages.index') }}">
                <div class="row">
                    <div class="col-md-3">
                        <select name="status" class="form-control">
                            <option value="">All Status</option>
                            <option value="unread" {{ request('status') == 'unread' ? 'selected' : '' }}>Unread</option>
                            <option value="read" {{ request('status') == 'read' ? 'selected' : '' }}>Read</option>
                            <option value="responded" {{ request('status') == 'responded' ? 'selected' : '' }}>Responded</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="type" class="form-control">
                            <option value="">All Types</option>
                            <option value="inquiry" {{ request('type') == 'inquiry' ? 'selected' : '' }}>Inquiry</option>
                            <option value="complaint" {{ request('type') == 'complaint' ? 'selected' : '' }}>Complaint</option>
                            <option value="support" {{ request('type') == 'support' ? 'selected' : '' }}>Support</option>
                            <option value="feedback" {{ request('type') == 'feedback' ? 'selected' : '' }}>Feedback</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" placeholder="Search by name, email or subject" value="{{ request('search') }}">
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="{{ route('admin.messages.index') }}" class="btn btn-secondary">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Bulk Actions -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-warning">Bulk Actions</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.messages.bulkUpdate') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <select name="action" class="form-control" required>
                            <option value="">Select Action</option>
                            <option value="mark_read">Mark as Read</option>
                            <option value="mark_unread">Mark as Unread</option>
                            <option value="delete">Delete</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <button type="submit" class="btn btn-warning">Apply to Selected</button>
                    </div>
                </div>
                <div id="selected-messages"></div>
            </form>
        </div>
    </div>

    <!-- Messages Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Contact Messages</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Subject</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Related</th>
                            <th>Received</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($messages as $message)
                        <tr class="{{ $message->is_read ? '' : 'table-warning' }}">
                            <td><input type="checkbox" class="message-checkbox" value="{{ $message->id }}"></td>
                            <td>{{ $message->id }}</td>
                            <td>{{ $message->name }}</td>
                            <td>{{ $message->email }}</td>
                            <td>{{ Str::limit($message->subject, 30) }}</td>
                            <td>
                                <span class="badge badge-{{ $message->type == 'complaint' ? 'danger' : ($message->type == 'inquiry' ? 'info' : ($message->type == 'support' ? 'warning' : 'success')) }}">
                                    {{ ucfirst($message->type ?? 'inquiry') }}
                                </span>
                            </td>
                            <td>
                                @if($message->admin_response)
                                <span class="badge badge-success">Responded</span>
                                @elseif($message->is_read)
                                <span class="badge badge-info">Read</span>
                                @else
                                <span class="badge badge-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                @if($message->tutor_job_id)
                                <span class="badge badge-primary">Job #{{ $message->tutor_job_id }}</span>
                                @elseif($message->tutor_id)
                                <span class="badge badge-info">Tutor #{{ $message->tutor_id }}</span>
                                @else
                                <span class="badge badge-secondary">General</span>
                                @endif
                            </td>
                            <td>{{ $message->created_at->format('M d, Y H:i') }}</td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-info btn-sm" onclick="showMessage({{ $message->id }})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if(!$message->admin_response)
                                    <button class="btn btn-success btn-sm" onclick="respondToMessage({{ $message->id }})">
                                        <i class="fas fa-reply"></i>
                                    </button>
                                    @endif
                                    @if(!$message->is_read)
                                    <form action="{{ route('admin.messages.markRead', $message) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-warning btn-sm">
                                            <i class="fas fa-envelope-open"></i>
                                        </button>
                                    </form>
                                    @endif
                                    <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this message?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $messages->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Message Detail Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Message Details</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="messageContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Response Modal -->
<div class="modal fade" id="responseModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Respond to Message</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form id="responseForm">
                @csrf
                <div class="modal-body">
                    <div id="originalMessage"></div>
                    <hr>
                    <div class="form-group">
                        <label for="response">Your Response:</label>
                        <textarea class="form-control" id="response" name="response" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Response</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAll = document.getElementById('select-all');
    const checkboxes = document.querySelectorAll('.message-checkbox');
    const selectedMessagesDiv = document.getElementById('selected-messages');

    selectAll.addEventListener('change', function() {
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedMessages();
    });

    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedMessages);
    });

    function updateSelectedMessages() {
        const selected = Array.from(checkboxes)
            .filter(cb => cb.checked)
            .map(cb => cb.value);
        
        selectedMessagesDiv.innerHTML = selected.map(id => 
            `<input type="hidden" name="message_ids[]" value="${id}">`
        ).join('');
    }
});

function showMessage(messageId) {
    fetch(`/admin/messages/${messageId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('messageContent').innerHTML = `
                <div class="card">
                    <div class="card-header">
                        <h6>From: ${data.name} (${data.email})</h6>
                        <small class="text-muted">Subject: ${data.subject}</small>
                    </div>
                    <div class="card-body">
                        <p>${data.message}</p>
                        ${data.admin_response ? `
                            <hr>
                            <h6>Admin Response:</h6>
                            <p class="text-primary">${data.admin_response}</p>
                            <small class="text-muted">Responded at: ${data.responded_at}</small>
                        ` : ''}
                    </div>
                </div>
            `;
            $('#messageModal').modal('show');
        });
}

function respondToMessage(messageId) {
    fetch(`/admin/messages/${messageId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('originalMessage').innerHTML = `
                <div class="card bg-light">
                    <div class="card-header">
                        <h6>Original Message from: ${data.name} (${data.email})</h6>
                        <small class="text-muted">Subject: ${data.subject}</small>
                    </div>
                    <div class="card-body">
                        <p>${data.message}</p>
                    </div>
                </div>
            `;
            
            document.getElementById('responseForm').action = `/admin/messages/${messageId}/respond`;
            $('#responseModal').modal('show');
        });
}

document.getElementById('responseForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const url = this.action;
    
    fetch(url, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#responseModal').modal('hide');
            location.reload();
        } else {
            alert('Error sending response');
        }
    });
});
</script>

@if(session('success'))
<script>
    $(document).ready(function() {
        toastr.success('{{ session('success') }}');
    });
</script>
@endif

@if(session('error'))
<script>
    $(document).ready(function() {
        toastr.error('{{ session('error') }}');
    });
</script>
@endif
@endsection
