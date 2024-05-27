@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Direktorat
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-direktorat">
                        @csrf

                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_dir">DIREKTORAT</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $description ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('direktorat.list') }}" class="btn btn-secondary btn-xs close-btn"><i
                                    class="fa fa-times mr-1"></i>
                                Cancel</a>
                            <a id="kirim" class="btn btn-success btn-xs close-modal"><i class="fa fa-save mr-1"></i>
                                Save</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            //var kd_div = @isset($kode_div)'{{ $kode_div }}'@else''@endisset;
            //var kd_dir = @isset($kode_dir)'{{ $kode_dir }}'@else''@endisset;

            $("#kirim").click(function(e) {
                e.preventDefault();
                var formdata = new FormData($("#form-direktorat")[0]);
                
                $.ajax({
                    url: @isset($id) "{{ route('direktorat.saveEditDirek') }}"  @else "{{ route('direktorat.storeDirek') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('direktorat.list') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });
    </script>
@endsection