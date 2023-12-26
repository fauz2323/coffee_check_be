@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <div class="profile-bg-picture" style="background-image:url({{ asset('assets/images/bg-profile.jpg') }})">
                <span class="picture-bg-overlay"></span>
                <!-- overlay -->
            </div>
            <!-- meta -->
            <div class="profile-user-box">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="profile-user-img"><img src="{{ asset('assets/images/users/avatar-1.jpg') }}"
                                alt="" class="avatar-lg rounded-circle"></div>
                        <div class="">
                            <h4 class="mt-4 fs-17 ellipsis">{{ $user->name }}</h4>
                            <p class="font-13"> User Experience Specialist</p>
                            <p class="text-muted mb-0"><small>{{ $user->email }}</small></p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="d-flex justify-content-end align-items-center gap-2">
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                <i class="ri-settings-2-line align-text-bottom me-1 fs-16 lh-1"></i>
                                Edit Password
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ meta -->
        </div>
    </div>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">History Upload List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="users-table" class="display min-w850">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>uuid</th>
                                        <th>Name</th>
                                        <th>red</th>
                                        <th>green</th>
                                        <th>blue</th>
                                        <th>date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Edit Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('user.changePassword', $user->id) }}" method="post">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="exampleFormControlInput1" class="form-label">New Password</label>
                            <input type="password" name="password" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection


@push('script')
    <script>
        $(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '',
                columns: [{
                        data: 'no',
                        name: 'id',
                        render: function(data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        },
                        orderable: false,
                        searchable: false,
                    },
                    {
                        data: 'uuid',
                        name: 'uuid'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'red',
                        name: 'red'
                    },
                    {
                        data: 'green',
                        name: 'green'
                    },
                    {
                        data: 'blue',
                        name: 'blue'
                    },
                    {
                        data: 'date',
                        name: 'date'
                    }
                ]
            });
        });
    </script>
@endpush
