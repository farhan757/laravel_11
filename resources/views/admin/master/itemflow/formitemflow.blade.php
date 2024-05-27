@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Item Flow
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-itemflow">
                        @csrf

                        <input type="hidden" name="itemgrp" id="itemgrp" value="{{ $itemgrp ?? '' }}">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subitem">SUB GROUP ITEM CODE</label>
                                        <select name="subitem" id="subitem" class="form-control">
                                            @isset($itemgrp)
                                            @foreach ($sptgroupitemsubexist as $item)
                                                <option value="{{ $item->subitem }}"
                                                    @isset($id) 
                                                    @if ($itemgrp == $item->subitem)
                                                        {{ 'selected' }}
                                                    @endif 
                                                @endisset>
                                                    {{ $item->description }}</option>
                                            @endforeach
                                            @endisset
                                            
                                            @foreach ($sptgroupitemsub as $item)
                                            <option value="{{ $item->subitem }}">
                                                {{ $item->description }}</option>
                                        @endforeach
                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="nama_flow">NAMA FLOW</label>
                                        <input type="text" class="form-control" name="nama_flow" id="nama_flow"
                                            value="@isset($itemgrp){{ $data[0]->nama_flow }}@endisset">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="itemflow">Nama Flow Step</label>
                                        <select name="itemflow[]" style="height: 150px;" id="itemflow" class="form-control"
                                            multiple="multiple">
                                            @foreach ($itemflow as $item)
                                                <option value="{{ $item->kodeitem_flow }}">{{ $item->namaitem_flow }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="toitemflow">To Flow Step</label>
                                        <select name="toitemflow[]" style="height: 150px;" id="toitemflow"
                                            class="form-control" multiple="multiple">
                                            @isset($itemgrp)
                                            @foreach ($data as $item)
                                                <option value="{{ $item->kodeitem_flow }}" selected>{{ $item->namaitem_flow }}
                                                </option>
                                            @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('itemflow.listItemFlow') }}" class="btn btn-secondary btn-xs close-btn"><i
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
            
            $(".multi").select2();
            $("#kirim").click(function(e) {
                e.preventDefault();
                var itemgrp = $("#itemgrp").val();
                var subitem = $("#subitem").val();
                var nama_flow = $("#nama_flow").val();
                //var formdata = new FormData($("#form-itemflow")[0]);
                var dataToItemFlow = [];
                var itemflow = $("#toitemflow option").length;
                for(var i = 0; i<itemflow; i++){
                    var dtval = $("#toitemflow option").eq(i).val();
                    dataToItemFlow.push(dtval);
                }
                console.log(dataToItemFlow);

                $.ajax({
                    url: @isset($itemgrp)
                        "{{ route('itemflow.storeEditFlowItem') }}"
                    @else
                        "{{ route('itemflow.storeFlowItem') }}"
                    @endisset ,
                    type: "POST",
                    data: { 
                        _token: "{{ csrf_token() }}", 
                        itemgrp:itemgrp,
                        subitem:subitem,
                        nama_flow:nama_flow, 
                        toitemflow:dataToItemFlow
                    },
                    //processData: false,
                    //contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status, rs.message, rs.status,
                            "{{ route('itemflow.listItemFlow') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status, rs.message, rs.status, null);
                    }
                });
            });

            $('#itemflow').click(function() {
                return !$('#itemflow option:selected').remove().appendTo('#toitemflow');
            });

            $('#toitemflow').click(function() {
                return !$('#toitemflow option:selected').remove().appendTo('#itemflow');
            });
        });
    </script>
@endsection
