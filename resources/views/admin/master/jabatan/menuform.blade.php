<div class="modal fade" id="modal-menuform">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">

      <div class="modal-header">
        <h4 class="modal-title" id="title-menuform">Menu</h4>
      </div>
        <!-- /.box-header -->
        <form method="POST" name='form-menu' id='form-menu'>
          {{ csrf_field() }}
          <input type="hidden" name="id" id="id-menuform">
          <div class="box-body">
          <div class="col-lg-12 table-responsive p-0" style="height: 400px;">
            <table class="table table-bordered table-head-fixed text-nowrap">
              <tr>
                <th style="width: 10px">#</th>
                <th>Name</th>
                <th>Desc</th>
              </tr>
              @foreach($menus as $index=>$value)

              <tr>
                <td>
                  <label>
                    <input type="checkbox" name="checkbox[]" id="{{ $value->id }}-menu" value="{{ $value->id }}">
                  </label>
                </td>
                <td>
                  @if($value->parent_id=='0')
                  <h3>
                  @endif
                  <strong>{{ $value->nama_menu }}</strong>
                  @if($value->parent_id=='0')
                  </h3>
                  @endif
              </td>
                <td> </td>
              </tr>
              @if(count($value->contents)>0)
                  @foreach($value->contents as $index2=>$value2)
                  <tr>
                    <td>

                    </td>
                    <td>
                      <label>
                        <input type="checkbox" name="checkbox[]" id="{{ $value2->id }}-menu" value="{{ $value2->id }}">
                      </label>
                    {{ $value2->nama_menu }}</td>
                    <td>submenu</td>
                  </tr>
                  @endforeach
              @endif
              @endforeach
            </table>
          </div>
          </div>
          <!-- /.box-body -->
          <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary pull-right">Save</button>
          </div>
        </form>
    </div>
    <!-- /.modal-content -->
  </div>

  <!-- /.modal-dialog -->
</div>
