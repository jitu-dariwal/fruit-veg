@extends('layouts.admin.app')

@section('content')
    <!-- Main content -->
    <section class="content">
    @include('layouts.errors-and-messages')
    <!-- Default box -->
      <div class="box">
          <form action="{{ route('admin.roles.permissions.save') }}" method="post" class="form">
          {{ csrf_field() }}
          <div class="box-body">
              <h2> Manage Permissions</h2>
              <div class="table-responsive">
              <table class="table table-bordered table_permission table-striped">
                  <thead class="thead-light">
                      <tr>
                          <th class="td_perm_name td_divider">Permissions</th>
                          @foreach ($roles as $role)
                          <th class="td_divider" @if($role->display_name == 'Technical Support Team') style="width:18%" @else  style="width: {{$grid_perc}}%;" @endif>{{$role->display_name}}</th>
                          @endforeach
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($perms as $module => $perm)        
                      <tr>
                          <td colspan='100' class="td_perm_name td_divider td_module_name"><strong>{{strtoupper($module)}}</strong></td>
                      </tr>
                      @foreach ($perm as $slug => $p)
                      <tr>
                          <td class="td_perm_name td_divider">{{$p->display_name}}</td>
                          @foreach ($roles as $role)
                          <td class="td_perm_export td_divider">
                              <label class="switch switch-icon switch-pill switch-success" style="margin-bottom: 0px;" title="{{$p->display_name .' | '.$role->display_name}}">                                                  
                                <input type="checkbox" class="switch-input" name="perm[{{$role->id}}][{{$p->id}}]" value="{{$slug}}" {{ isset($active_perm[$role->id][$p->id]) ?  'checked' : '' }}>
                                <span class="switch-label" data-on="&#xf00c" data-off="&#xf00d"></span>
                                <span class="switch-handle"></span>
                              </label>

                          </td>
                          @endforeach
                          <!-- <td class="td_perm_spacer">&nbsp;</td>-->
                      </tr>
                      @endforeach
                      @endforeach
                  </tbody>
              </table>
              </div>
          </div>
          <div class="box-footer">
              <div class="btn-group">
                  <button type="submit" class="btn btn-primary">Save</button>
              </div>
          </div>
          </form>  
          <!-- /.box-body -->
      </div>
      <!-- /.box -->            
    </section>
    
    <!-- /.content -->
@endsection
