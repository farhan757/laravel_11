@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
           Sub Group Item
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-sptgroupitemsub">
                        @csrf

                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="groupitem">GROUP ITEM CODE</label>
                                        <select name="groupitem" id="groupitem" class="form-control">
                                            @foreach ($sptgroupitem as $item)
                                                <option value="{{ $item->groupitem }}"
                                                @isset($id) 
                                                    @if ($data->groupitem == $item->groupitem)
                                                        {{ 'selected' }}
                                                    @endif 
                                                @endisset>[{{ $item->groupitem }}] - {{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="subitem">SUB ITEM CODE</label>
                                        <input type="text" class="form-control" maxlength="5" id="subitem" name="subitem" value="@isset($id){{  $data->subitem }}@endisset">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">DESCRIPTION</label>
                                        <input type="text" class="form-control" id="description" name="description" maxlength="100" value="@isset($id){{ $data->description }}@endisset">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox"  name="flag_qty" id="flag_qty" 
                                                @if ($field_qty == 1)
                                                    {{ 'checked' }}
                                                @endif> Enable Field Qty
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="flag_amount" id="flag_amount"
                                                @if ($field_amount == 1)
                                                    {{ 'checked' }}
                                                @endif> Enable Field Amount
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> 
                        {{-- tutup clas box-body --}}
                        <div class="modal-footer">
                            <a href="{{ route('sptgroupitemsub.listSptGroupSub') }}" class="btn btn-secondary btn-xs close-btn"><i
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

            $("#kirim").click(function(e) {
                e.preventDefault();
                var formdata = new FormData($("#form-sptgroupitemsub")[0]);
                
                $.ajax({
                    url: @isset($id) "{{ route('sptgroupitemsub.storeEdit') }}"  @else "{{ route('sptgroupitemsub.storeSptGroupItemSub') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('sptgroupitemsub.listSptGroupSub') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });
    </script>
@endsection