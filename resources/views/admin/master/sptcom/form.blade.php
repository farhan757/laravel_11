@extends('layouts.app')

@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Form Add Param
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-list"></i> Form Add Param</a></li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->

        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h2 class="box-title">Form</h2>
                    </div>
                    @if (Session::has('error'))
                        <code>{{ Session::get('error') }}</code>
                    @endif
                    @if (Session::has('success'))
                        <div class="alert alert-success">
                            <p>{{ Session::get('success') }}</p>
                        </div>
                    @endif
                    <form id="form-cek" method="POST" action="@if(isset($fieldcd)) {{ route('sptcom.saveparam') }} @else {{ route('sptcom.add') }} @endif" autocomplete="off">
                        @csrf
                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">
                            <div class="row">

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fieldcd">*Code</label>
                                        <input type="text" class="form-control" name="fieldcd" id="fieldcd" value="{{ $fieldcd ?? '' }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">*Description</label>
                                        <input type="text" class="form-control" name="description" id="description" value="{{ $description ?? '' }}"
                                            required>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" id="customCheck1" name="c_grp" onclick="myFunction()">
                                            New Group
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" id="s_grp">
                                    <div class="form-group">
                                        <label for="nfieldnm">*Group</label>
                                        <select name="nfieldnm" id="nfieldnm" class="form-control" onchange="changeCategory()">
                                            <option value="">--PILIH--</option>
                                            @foreach ($data as $item)
                                                <option value="{{ $item->fieldnm }}"
                                                    @isset($fieldnm)
                                                        {{ $fieldnm == $item->fieldnm ? 'selected' : '' }}
                                                    @endisset
                                                    >{{ $item->fieldnm }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6" style="display: none" id="s_grp2">
                                    <div class="form-group">
                                        <label for="fieldnm">*Group</label>
                                        <input type="text" class="form-control" name="fieldnm" id="fieldnm" value="{{ $fieldnm ?? '' }}" required>
                                    </div>
                                </div>
                            </div>

                            <div class="footer">
                                <button class="btn btn-success"><span class="fa fa-send"></span>
                                    Submit</button>
                                <a href="{{ route('sptcom.list') }}" class="btn btn-warning button_action"><span
                                        class="fa fa-undo"></span>
                                    Back</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->

@endsection

@section('js')
    <script>
        function changeCategory(){
            var valcat = document.getElementById("nfieldnm").value;
            document.getElementById("fieldnm").value = valcat;
        }

        function myFunction() {
            // Get the checkbox
            var checkBox = document.getElementById("customCheck1");
            // Get the output text
            var text = document.getElementById("s_grp");
            var text2 = document.getElementById("s_grp2");
            // If the checkbox is checked, display the output text
            if (checkBox.checked == true) {
                text.style.display = "none";
                text2.style.display = "block";
            } else {
                text.style.display = "block";
                text2.style.display = "none";
            }
        }
    </script>
@endsection
