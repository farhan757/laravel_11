@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Item
        </h1>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">

                    </div>
                    <form class="form" autocomplete="off" id="form-sptitem">
                        @csrf

                        <input type="hidden" name="id" id="id" value="{{ $id ?? '' }}">
                        <div class="box-body">

                            <div class="row">

                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="subitem">SUB GROUP ITEM CODE</label>
                                        <select name="subitem" id="subitem" class="form-control">
                                            @foreach ($sptgroupitemsub as $item)
                                                <option value="{{ $item->subitem }}"
                                                @isset($id) 
                                                    @if ($data->subitem == $item->subitem)
                                                        {{ 'selected' }}
                                                    @endif 
                                                @endisset>[{{ $item->subitem }}]-{{ $item->description }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="itemcd">ITEM CODE</label>
                                        <input type="text" class="form-control" maxlength="10" id="itemcd" name="itemcd" value="@isset($id){{  $data->itemcd }}@endisset">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="description">DESCRIPTION</label>
                                        <input type="text" class="form-control" id="description" name="description" maxlength="100" value="@isset($id){{ $data->Description }}@endisset">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <a href="{{ route('sptitem.listSptItems') }}" class="btn btn-secondary btn-xs close-btn"><i
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
                var formdata = new FormData($("#form-sptitem")[0]);
                
                $.ajax({
                    url: @isset($id) "{{ route('sptitem.storeEdit') }}"  @else "{{ route('sptitem.storeSptItem') }}" @endisset,
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    success: function(rs) {
                        console.log(rs);
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,"{{ route('sptitem.listSptItems') }}");
                    },
                    error: function(rs) {
                        msgBoxSweetBasic(rs.status,rs.message,rs.status,null);
                    }
                });
            });
        });
    </script>
@endsection