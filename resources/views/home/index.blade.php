@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Summary Today
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-xs-6">

                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $data->pending ?? 0 }}</h3>
                        <p>Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-clock-o"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">

                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ $data->progress ?? 0 }}</h3>
                        <p>Progress</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">

                <div class="small-box bg-red">
                    <div class="inner">
                        <h3>{{ $data->reject ?? 0 }}</h3>
                        <p>Reject</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-close"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>

            <div class="col-lg-3 col-xs-6">

                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ $data->approve ?? 0 }}</h3>
                        <p>Approved</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-check"></i>
                    </div>
                    {{-- <a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a> --}}
                </div>
            </div>

        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('js')
@endsection
