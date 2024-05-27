@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Department
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-dept">
                        @csrf

                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_dir">DIREKTORAT</label>
                                        <select name="kode_dir" id="kode_dir" onchange="getDivisi(this.value)"
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
                                        <label for="kode_div">DIVISI</label>
                                        <select name="kode_div" id="kode_div"
                                            class="form-control select2" style="width: 100%;" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">DEPARTMENT</label>
                                        <input type="text" class="form-control" id="description" name="description" value="{{ $description ?? '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('department.list') }}" class="btn btn-secondary btn-xs close-btn"><i
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
            var kd_div = @isset($kode_div)'{{ $kode_div }}'@else''@endisset;
            var kd_dir = @isset($kode_dir)'{{ $kode_dir }}'@else''@endisset;
            getDivisi(kd_dir);

            $("#kirim").click(function(e) {
                e.preventDefault();
                var formdata = new FormData($("#form-dept")[0]);
                
                $.ajax({
                    url: @isset($id) "{{ route('department.saveEditDept') }}"  @else "{{ route('department.storeDept') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('department.list') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });

        function getDivisi(kd_dir) {
            var kode_dir = kd_dir;
            var kode_div = "{{ $kode_div ?? '' }}";

            if (kode_dir != "") {
                $.ajax({
                    url: "{{ route('getdivisi') }}",
                    type: "GET",
                    data: {
                        prefixx: kode_dir
                    },
                    success: function(rsp) {
                        
                        
                        var divisi_html = "<option value=''></option>";
                        
                        for (let index = 0; index < rsp.length; index++) {
                            const element = rsp[index];
                            var selected = '';
                            if(kode_div === element.kode_div){
                                selected = 'selected';
                            }

                            divisi_html += "<option value='" + element.kode_div + "' "+selected+">[" + element.kode_div +
                                "]" +
                                element.nama_div + "</option>";
                            
                        }
                        $("#kode_div").html(divisi_html);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
                //getDept(kode_dir)
            }
        }
    </script>
@endsection