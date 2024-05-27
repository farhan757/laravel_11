@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Divisi
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-divisi">
                        @csrf

                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_dir">DIREKTORAT </label>
                                        <select name="kode_dir" id="kode_dir" 
                                            class="form-control select2" style="width: 100%;" required>
                                            <option value=""></option>
                                            @forelse ($direk as $item)
                                                @if ($item->kode_dir != "99")
                                                <option value="{{ $item->kode_dir }}" @isset($kode_dir)
                                                    {{ $kode_dir == $item->kode_dir ? 'selected' : '' }}
                                                @endisset>[{{ $item->kode_dir }}]
                                                    {{ $item->nama_dir }}</option>
                                                @endif
                                            @empty
                                                <option value="">Empty</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">DIVISI</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $description ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('divisi.list') }}" class="btn btn-secondary btn-xs close-btn"><i
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
            var kd_dir = @isset($kode_dir)'{{ $kode_dir }}'@else''@endisset;

            $("#kirim").click(function(e) {
                e.preventDefault();
                var formdata = new FormData($("#form-divisi")[0]);
                
                $.ajax({
                    url: @isset($id) "{{ route('divisi.saveEditDivisi') }}"  @else "{{ route('divisi.storeDivisi') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('divisi.list') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });
    </script>
@endsection