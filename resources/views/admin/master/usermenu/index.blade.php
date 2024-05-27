@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            User To Menu
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
                        {{-- <a href="{{ route('sptitem.showForm') }}" class="btn btn-success pull-right"><span
                                class="fa fa-plus"></span> Item</a> --}}
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
                                        <th>NIP</th>
                                        <th>Nama</th>
                                        {{-- <th>Created at</th> --}}
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $val)
                                        <tr>
                                            <td>{{ $loop->iteration + $data->firstItem() - 1 }}</td>
                                            <td>{{ $val->nip }}</td>
                                            <td>{{ $val->nama_lengkap }}</td>
                                            {{-- <td>{{ $val->created_at }}</td> --}}
                                            <td style="width: 100pt">

                                                <a href="#" title="view menu" data-toggle="tooltip"
                                                    onclick="menuForm({{ $val->nip }})"
                                                    class="btn btn-primary btn-xs"><i class="fa fa-bars"></i> Menu</a>
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
    @include('admin.master.usermenu.menuform');
@endsection

@section('js')
    <script>

        // function unCheckMenu() {

        //     @foreach ($menus as $key => $value)
        //         $("#{{ $value->id }}-menu").prop("checked", false);
        //         @foreach ($value->contents as $key2 => $value2)
        //             $("#{{ $value2->id }}-menu").prop("checked", false);
        //         @endforeach
        //     @endforeach
        // }

        function menuForm(id) {
            save_method = "menu";
            $('#title-menuform').text('Menu');
            $('#modal-menuform form')[0].reset();

            $.ajax({
                url: "{{ route('getusermenu') }}",
                type: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    kode_user: id
                },
                dataType: "JSON",
                success: function(data) {
                    // console.log(data);

                    $('#modal-menuform').modal('show');
                    $('#title-form').text('Menu');
                    $('#id-menuform').val(id);
                    for (var key in data) {
                        var obj = data[key];
                        console.log(obj);
                        $("#" + obj.id_menu + "-menu").prop("checked", true);
                    }
                },
                error: function() {

                    alert("Nothing Data");
                }
            });
        }

        $(function() {

            $("#form-menu").submit(function(e) {
                e.preventDefault();
                //var formdata = new FormData($("#demo-form2")[0]);
                var id = $('#id-menuform').val();
                $.ajax({
                    url: "replaceusermenu/" + id,
                    type: "POST",
                    data: $('#form-menu').serializeArray(),
                    //processData: false,
                    //contentType: false,
                    success: function(rs) {
                        //hideSpin("loading");
                        console.log(rs);
                        alert(rs.message);
                        location.reload();
                    },
                    error: function(rs) {
                        //hideSpin("loading");
                        console.log(rs);
                    }
                });
            });


        });
    </script>
@endsection
