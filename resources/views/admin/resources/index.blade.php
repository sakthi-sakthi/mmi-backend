@extends('admin.layouts.master')

@section('title')
    {{ __('main.resources') }}
@endsection

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h4 class="m-0 text-dark">{{ __('main.resources') }}</h4>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">{{ __('main.Home') }}</a>
                            </li>
                            <li class="breadcrumb-item active">{{ __('main.resources') }}</li>
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
                        <a href="{{ route('admin.resource.create') }}"
                            class="btn btn-success btn-sm">{{ __('main.Add New') }}</a>
                        <a href="{{ route('admin.resource.trash') }}" class="btn btn-warning btn-sm float-right"><i
                                class="fas fa-trash-alt"></i>{{ __('main.Recycle') }}</a>
                    </div>
                    <div class="card-body">
                        <table id="table1" class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>{{ __('main.id') }}</th>
                                    <th>{{ __('main.Title') }}</th>
                                    <th>{{ __('main.Category') }}</th>
                                    <th>{{ __('main.Creation Date') }}</th>
                                    <th>{{ __('main.Statu') }}</th>
                                    <th>{{ __('main.Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($articles as $key => $article)
                                    <tr>
                                        {{-- <td>
                                            @if ($article->getMedia)
                                            <img src="{{ $article->getMedia->id == 1 ? '' : $article->getMedia->getUrl('thumb') }}" alt=""
                                            style="height: 54px">
                                            @endif
                                            </td> --}}
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $article->title }}</td>
                                        <td>{{ $article->getCategory->title }}</td>
                                        <td>{{ $article->created_at->diffForHumans() }}</td>
                                        <td><input class="switch" type="checkbox" name="my-checkbox"
                                                data-id="{{ $article->id }}" @if ($article->status == 1) checked @endif
                                                data-toggle="toggle" data-size="mini"
                                                data-on="{{ __('main.Published') }}" data-off="{{ __('main.Draft') }}"
                                                data-onstyle="success" data-offstyle="danger"></td>
                                        <td>
                                            {{-- <a href="{{ url('/', $article->getSlug->slug) }}"
                                                title="{{ __('main.Show') }}" class="btn btn-success btn-xs"><i
                                                    class="fas fa-arrow-right"></i></a> --}}
                                            <a href="{{ route('admin.resource.edit', $article->id) }}"
                                                title="{{ __('main.Edit') }}" class="btn btn-primary btn-xs"><i
                                                    class="fas fa-pencil-alt"></i></a>
                                            <a href="{{ route('admin.resource.delete', $article->id) }}"
                                                onclick="confirmDelete(event,{{ $article->id }})" title="{{ __('main.Delete') }}"
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
            id = $(this).attr('data-id');
            status = $(this).prop('checked');
            $.get("{{ route('admin.resource.switch') }}", {
                id: id,
                status: status
            })
        })
    </script>
@endsection
