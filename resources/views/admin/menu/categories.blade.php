@extends('layout.admin-layout')
@section('title') @if( ! empty($title)) {{ $title }} | @endif @parent @endsection

@section('main')

    <div class="container">

        <div id="wrapper">

            @include('admin.sidebar_menu')

            <div id="page-wrapper">
                @if( ! empty($title))

                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header"> {{ $title }} </h1>
                        </div> <!-- /.col-lg-12 -->

                        <div class="col-lg-12 form-group {{ $errors->has('menu_type')? 'has-error':'' }}">
                            <label for="menu_type" class="col-sm-4 control-label">Төрөл</label>
                            <div class="col-sm-6">
                                <select class="form-control select2NoSearch" name="menu_type" id="menu_type">
                                    @foreach($menus as $menu)
                                        <option value="{{ $menu->id }}" {{ $type == $menu->id ? 'selected':'' }}>{{ $menu->name }}</option>
                                    @endforeach
                                </select>
                                {!! $errors->has('menu_type')? '<p class="help-block">'.$errors->first('menu_type').'</p>':'' !!}
                            </div>
                            <div class="col-sm-2">
                                <a href="/dashboard/menus/categories/{{ $type }}" class="btn btn-info pull-right"><i class="fa fa-user-plus"></i> Тохируулах</a>
                            </div>
                        </div>
                    </div> <!-- /.row -->
                @endif

                @include('admin.flash_msg')

                <div class="row">
                    <div class="col-xs-12">
                        <table class="table table-bordered">
                            <tr>
                                <th></th>
                                <th><small>Филтер</small> </th>
                            </tr>
                            @foreach($categories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <div class="clearfix">
                                            <strong>{{ $category->category_name }}</strong>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>   <!-- /#page-wrapper -->
        </div>   <!-- /#wrapper -->
    </div> <!-- /#container -->
@endsection

@section('page-js')

    <script>
        $('#menu_type').on('change', function () {
              var id = $(this).val(); // get selected value
              if (id) { 
                  window.location = `/dashboard/menus/categories?type=${id}`;
              }
              return false;
        });
    </script>
@endsection


