@extends('admin.layouts.master')

@section('title')
    {{ __('main.ourfeature') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.ourfeature') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('main.ourfeature') }}</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <div class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <a href="{{ route('admin.news_events.create') }}"
                            class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                        <a href="{{ route('admin.news_events.trash') }}" class="btn btn-warning btn-sm float-right"><i
                                class="fas fa-trash-alt"></i>{{ __('main.Recycle') }}</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <tr>
                                        <th>{{ __('main.id') }}</th>
                                        <th>{{ __('main.Title') }}</th>
                                        <th>{{ __('main.Category') }}</th>
                                        <th>{{ __('main.Statu') }}</th>
                                        <th>{{ __('main.Creation Date') }}</th>
                                        <th>{{ __('main.Action') }}</th>
                                    </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($newsevents as $key => $event)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $event->title }}</td>
                                        <td>{{ $event->getCategory->title }}</td>
                                        <td><input class="switch" type="checkbox" name="my-checkbox"
                                                data-id="{{ $event->id }}" @if ($event->status == 1) checked @endif
                                                data-toggle="toggle" data-size="mini"
                                                data-on="{{ __('main.Published') }}" data-off="{{ __('main.Draft') }}"
                                                data-onstyle="success" data-offstyle="danger"></td>
                                                <td>{{ $event->formatDate($event->created_at) }} </td>
                                        <td>
                                            {{-- <a href="{{ url('/', $article->getSlug->slug) }}"
                                                title="{{ __('main.Show') }}" class="btn btn-success btn-xs"><i
                                                    class="fas fa-arrow-right"></i></a> --}}
                                            <a href="{{ route('admin.news_events.edit', $event->id) }}"
                                                title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs"><i
                                                    class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('admin.news_events.delete', $event->id) }}"
                                                onclick="confirmDelete( event, {{ $event->id }})" title="{{ __('main.Delete') }}"
                                                class="btn btn-danger btn-xs"><i class="far fa-times-circle"></i></a>
                                        </td>
                                    </tr> 
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </div><!-- /.content -->
    </div>
@endsection

@section('script')
    <script>

$('.switch').change(function() {
    var id = $(this).attr('data-id');
    var status = $(this).prop('checked') == true? 1 : 0;
   $.ajax({
       url: "{{ route('admin.news_events.switch') }}",
       type: "POST",
       data: {
           "_token": "{{ csrf_token() }}",
           "id": id,
           "status": status
       },
       success: function(data) {
           console.log(data);
       }
   })
})
        // $('.switch').change(function() {

        //     id = $(this).attr('data-id');
        //     status = $(this).prop('checked');
        //     $.get("{{ route('admin.news_events.switch') }}", {
        //         id: id,
        //         status: status
        //     })
        // })

    </script>
@endsection
