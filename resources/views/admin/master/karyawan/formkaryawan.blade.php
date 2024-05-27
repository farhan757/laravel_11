@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Karyawan
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-karyawan">
                        @csrf

                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" class="form-control" name="nip" id="nip" value="{{ $nip ?? '' }}" @isset($nip)
                                            {{ 'readonly' }}
                                        @endisset required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="username">USERNAME</label>
                                        <input type="text" class="form-control" name="username" id="username" value="{{ $username ?? '' }}" @isset($username)
                                        {{ 'readonly' }}
                                    @endisset required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nama_lengkap">NAMA LENGKAP</label>
                                        <input type="text" class="form-control" name="nama_lengkap" id="nama_lengkap" value="{{ $nama_lengkap ?? '' }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="email">EMAIL</label>
                                        <input type="email" class="form-control" name="email" id="email" value="{{ $email ?? '' }}" required>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_cab">CABANG</label>
                                        <select name="kode_cab" id="kode_cab" class="form-control select2"
                                            style="width: 100%;" required>
                                            <option value=""></option>
                                            @forelse ($cabang as $item)
                                                <option value="{{ $item->kode_cab }}" @isset($kode_cab)
                                                    {{ $kode_cab == $item->kode_cab ? 'selected' : '' }}
                                                @endisset>[{{ $item->kode_cab }}]
                                                    {{ $item->nama_cab }}</option>
                                            @empty
                                                <option value="">Empty</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_jabat">JABATAN</label>
                                        <select name="kode_jabat" id="kode_jabat" class="form-control select2"
                                            style="width: 100%;" required>
                                            <option value=""></option>
                                            @forelse ($jabatan as $item)
                                                <option value="{{ $item->kode_jab }}" @isset($kode_jabat)
                                                    {{ $kode_jabat == $item->kode_jab ? 'selected' : '' }}
                                                @endisset>[{{ $item->kode_jab }}]
                                                    {{ $item->nama_jab }}</option>
                                            @empty
                                                <option value="">Empty</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_dir">DIREKTORAT</label>
                                        <select name="kode_dir" id="kode_dir" onchange="getDivisi(this.value)"
                                            class="form-control select2" style="width: 100%;" required>
                                            <option value=""></option>
                                            @forelse ($direk as $item)
                                                <option value="{{ $item->kode_dir }}" @isset($kode_dir)
                                                    {{ $kode_dir == $item->kode_dir ? 'selected' : '' }}
                                                @endisset>[{{ $item->kode_dir }}]
                                                    {{ $item->nama_dir }}</option>
                                            @empty
                                                <option value="">Empty</option>
                                            @endforelse
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_div">DIVISI</label>
                                        <select name="kode_div" id="kode_div" onchange="getDept(this.value)"
                                            class="form-control select2" style="width: 100%;" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_dept">DEPARTMENT</label>
                                        <select name="kode_dept" id="kode_dept" class="form-control select2"
                                            style="width: 100%;" required>
                                            <option value=""></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <ul style="font-size: 8pt">
                                            <li>Kode Jabatan 5 dan 4 tidak perlu mengisi Department</li>
                                            <li>Kode Jabatan 1,2 dan 3 tidak perlu mengisi Department dan Divisi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            
                            <a href="{{ route('karyawan.list') }}" class="btn btn-secondary btn-xs close-btn" data-dismiss="modal"><i
                                    class="fa fa-times mr-1"></i>
                                Cancel</a>
                            <a id="submit" class="btn btn-success btn-xs close-modal"><i class="fa fa-save mr-1"></i>
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
            getDept(kd_div);

            $("#submit").click(function(e) {
                var formdata = new FormData($("#form-karyawan")[0]);

                $.ajax({
                    url: @isset($nip) "{{ route('karyawan.edit') }}"  @else "{{ route('karyawan.store') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('karyawan.list') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });

        function getDept(kd_div) {
            var kode_div = kd_div;
            var kode_dept = "{{ $kode_dept ?? '' }}";
            
            if (kode_div != "") {    
                $.ajax({
                    url: "{{ route('getdept') }}",
                    type: "GET",
                    data: {
                        prefix: kode_div
                    },
                    success: function(rsp) {
                        console.log(rsp);
                        var divisi_html = "<option value=''></option>";
                        
                        for (let index = 0; index < rsp.length; index++) {
                            const element = rsp[index];
                            var selected = '';
                            if(kode_dept === element.kode_dep){
                                selected = 'selected';
                            }
                            divisi_html += "<option value='" + element.kode_dep + "' "+selected+">[" + element.kode_dep +
                                "]" +
                                element.nama_dep + "</option>";
                            
                        }
                        $("#kode_dept").html(divisi_html);
                    },
                    error: function(err) {
                        console.log(err);
                    }
                });
            }
        }

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