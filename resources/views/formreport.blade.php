@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Form Report
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" method="get" action="{{ route('report.showreport') }}" autocomplete="off"
                        id="form-report">
                        @csrf

                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="keyselect">Field (default jika dikosongkan)</label>
                                        <select name="keyselect[]" id="keyselect" class="form-control multi"
                                            multiple="multiple" data-placeholder="Select Field">
                                            @foreach ($field as $key => $item)
                                                <option value="{{ $key }}">{{ $item }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="from">Request Date(From)</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right date" name="from"
                                                id="datepicker">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="to">Request Date(To)</label>
                                        <div class="input-group date">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" class="form-control pull-right date" name="to"
                                                id="datepicker">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="groupcd">Where [groupcd]</label>
                                        <select name="groupcd" id="groupcd" class="form-control"
                                            data-placeholder="Select Field">
                                            <option value=""></option>
                                            @foreach ($groupcd as $item)
                                                <option value="{{ $item->groupcd }}">{{ $item->groupcd }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="oper">[Or/And]</label>
                                        <select name="oper" id="oper" class="form-control">
                                            <option value=""></option>
                                            <option value="and">And</option>
                                            <option value="or">Or</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="itemgrp">Where [Itemgrp]</label>
                                        <select name="itemgrp[]" id="itemgrp" class="form-control multi"
                                            multiple="multiple">
                                            @foreach ($itemgrp as $item)
                                                <option value="{{ $item->subitem }}">{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="dept">Departemen (default jika dikosongkan)</label>
                                        <select name="dept[]" id="dept" class="form-control multi"
                                            multiple="multiple" data-placeholder="Select Field">
                                            @foreach ($dept as $key => $item)
                                                <option value="{{ $item->fieldcd }}">{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="nmfile">Nama File</label>
                                        <div class="input-group ">
                                            <div class="input-group-addon">
                                                <i class="fa fa-file-excel-o"></i>
                                            </div>
                                            <input type="text" maxlength="35" class="form-control pull-right file" name="nmfile"
                                                id="nmfile" required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="submit" id="submit" class="btn btn-success btn-xs close-modal"><i
                                    class="fa fa-save mr-1"></i>
                                Generate</button>
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
            $('.multi').select2();
            $('.date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd'
            })

            document.getElementById("form-report").onsubmit = function() {
                //window.open('', '_blank');
                this.target = '_blank';
            };
        });
    </script>
@endsection
