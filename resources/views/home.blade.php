@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-xxl-6 col-sm-6">
                <div class="card widget-flat text-bg-pink">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-eye-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">User Aplikasi</h6>
                        <h2 class="my-2">{{ $userCount }}</h2>
                    </div>
                </div>
            </div> <!-- end col-->

            <div class="col-xxl-6 col-sm-6">
                <div class="card widget-flat text-bg-purple">
                    <div class="card-body">
                        <div class="float-end">
                            <i class="ri-wallet-2-line widget-icon"></i>
                        </div>
                        <h6 class="text-uppercase mt-0" title="Customers">History</h6>
                        <h2 class="my-2">{{ $historyCount }}</h2>

                    </div>
                </div>
            </div> <!-- end col-->

        </div>
    </div>
@endsection
