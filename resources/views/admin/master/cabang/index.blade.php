@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Cabang
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Filter</h2>
                        <a href="{{ route('cabang.showformcabang') }}" class="btn btn-success pull-right"><span
                                class="fa fa-plus"></span> Cabang</a>
                    </div>
                    <form class="form-horizontal" autocomplete="off">
                        @csrf
                        <div class="box-body">

                            <div class="form-group">
                                <div class="col-md-5">
                                    <input type="text" class="form-control" name="cari" id="cari"
                                        value="{{ $cari }}" placeholder="Search" autofocus autocomplete="off">
                                </div>
                                {{-- <button type="reset" wire:click="search" class="btn btn-warning button_action"><span
                                        class="fa fa-times"></span>
                                    Reset</button> --}}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-body">
                        <div class="box-body table-responsive">
                            <table class="table table-bordered table-hover" style="font-size: 10px">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Branch Code</th>
                                        <th>Name</th>
                                        <th>Created at</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $val)
                                        <tr>
                                            <td>{{ $loop->iteration + $data->firstItem() - 1 }}</td>
                                            <td>{{ $val->fieldcd }}</td>
                                            <td>{{ $val->description }}</td>
                                           
                                            <td>{{ $val->created_at }}</td>
                                            <td style="width: 100pt">

                                                <a href="{{ route('cabang.showEditCab', ['id' => $val->id]) }}"
                                                    type="button" data-toggle="tooltip" title="edit" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-edit"></i></a>
                                                        
                                                <a onclick="deleteCab('{{ $val->id }}')" type="button"
                                                    title="delete" data-toggle="tooltip" class="btn btn-primary btn-xs"><i
                                                        class="fa fa-trash"></i></a>
                    
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $data->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection

@section('js')
    <script>
        function deleteCab(val) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to delete!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('cabang.deleteCab') }}",
                        type: "GET",
                        data: {
                            id: val
                        },
                        success: function(rs) {
                            msgBoxSweetBasic(rs.status, rs.message, rs.status,"{{ route('cabang.list') }}");
                        },
                        error: function(rs) {
                            msgBoxSweetBasic(rs.status, rs.message, rs.status, null);
                        }
                    });
                }
            });
        }
    </script>
@endsection
