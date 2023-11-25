@extends('layouts.app')

@section('content')
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
