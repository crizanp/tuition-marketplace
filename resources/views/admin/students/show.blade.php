@extends('admin.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Student Details</h1>
        <a href="{{ route('admin.students.index') }}" class="btn btn-secondary">Back to Students</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">{{ $student->name }}</h5>
                    <p class="mb-1"><strong>Email:</strong> {{ $student->email }}</p>
                    <p class="mb-1"><strong>Phone:</strong> {{ $student->phone ?? 'N/A' }}</p>
                    <p class="mb-1"><strong>Status:</strong>
                        <span class="badge badge-{{ $student->status == 'active' ? 'success' : ($student->status == 'suspended' ? 'danger' : 'secondary') }}">{{ ucfirst($student->status ?? 'active') }}</span>
                    </p>
                    <p class="mb-1"><strong>Joined:</strong> {{ $student->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                        <h6 class="mb-3">Quick Actions</h6>
                        <div class="d-flex gap-2">
                            @if (Route::has('admin.students.edit'))
                                <a href="{{ route('admin.students.edit', $student) }}" class="btn btn-primary btn-sm">Edit</a>
                            @else
                                <button class="btn btn-primary btn-sm" disabled>Edit</button>
                            @endif

                            @if (Route::has('admin.students.status'))
                                <form action="{{ route('admin.students.status', $student) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="{{ $student->status === 'suspended' ? 'active' : 'suspended' }}">
                                    <button type="submit" class="btn btn-sm {{ $student->status === 'suspended' ? 'btn-success' : 'btn-warning' }}">
                                        {{ $student->status === 'suspended' ? 'Activate' : 'Suspend' }}
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-warning" disabled>{{ $student->status === 'suspended' ? 'Activate' : 'Suspend' }}</button>
                            @endif
                        </div>
                    </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Vacancies ({{ $student->vacancies->count() }})</div>
                <div class="card-body">
                    @if($student->vacancies && $student->vacancies->count())
                        <ul class="list-group">
                            @foreach($student->vacancies as $vacancy)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $vacancy->title }}</strong>
                                        <div class="text-muted small">{{ $vacancy->subject }} — {{ $vacancy->grade_level }}</div>
                                    </div>
                                    <div class="text-end">
                                        <small class="text-muted">{{ $vacancy->created_at->format('M d, Y') }}</small>
                                        <div class="mt-1">
                                            <a href="{{ route('admin.vacancies.show', $vacancy) ?? '#' }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="mb-0">No vacancies posted by this student.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
