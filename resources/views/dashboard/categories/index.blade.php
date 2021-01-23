@extends('layouts.dashboard.app')

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1> @lang('site.categories')</h1>
            <ol class="breadcrumb">
                <li><a href="{{route('dashboard.index')}}"><i class="fa fa-dashboard"></i> @lang('site.dashboard')</a></li>
                <li class="active"> @lang('site.categories')</li>
            </ol>
            
        </section>
        <section class="content">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title" style="margin-bottom: 20px"> @lang('site.categories') <small>{{$categories->total()}}</small></h3>
                    <form action="{{route('dashboard.categories.index')}}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="@lang('site.search')" value="{{request()->search}}">
                            </div>
                            <div class="col-md-4">
                                <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i> @lang('site.search') </button>

                                @if (auth()->user()->hasPermission('categories_create'))

                                    <a href="{{route('dashboard.categories.create')}}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.add')</a>
                                @else
                                
                                    <a href="#" class="btn btn-primary disabled"><i class="fa fa-plus"></i> @lang('site.add')</a>


                                @endif

                            </div>
                        </div>
                    </form>
                </div>
                {{-- end of box-header --}}
                <div class="box-body">
                    

                    @if ($categories->count() > 0)

                        <table class="table table-hover">
                            <thead></thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('site.name')</th>
                                <th>@lang('site.products_count')</th>
                                <th>@lang('site.related_products')</th> 
                                <th>@lang('site.action')</th>
                            </tr>
                            <tbody>
                                @foreach ($categories as $index=>$category)
                                    <tr>
                                        <td>{{ $index + 1}}</td>
                                        <td>{{$category->name}}</td>
                                        <td>{{$category->products->count()}}</td>
                                        <td><a href="{{route('dashboard.products.index',['category_id' => $category->id])}}" class="btn btn-info btn-sm">@lang('site.related_products')</a></td>
                                        <td>

                                            @if (auth()->user()->hasPermission('categories_update'))
                                            
                                                <a href="{{route('dashboard.categories.edit', $category->id)}}" class="btn btn-info btn-sm"><i class="fa fa-edit"></i> @lang('site.edit')</a>
                                            @else
                                                
                                                <a href="#" class="btn btn-info btn-sm disabled"><i class="fa fa-edit"></i> @lang('site.edit')</a>


                                            @endif
                                           
                                            @if (auth()->user()->hasPermission('categories_delete'))

                                                <form action="{{route('dashboard.categories.destroy', $category->id)}}" method="POST" style="display: inline-block">
                                                    {{ csrf_field() }}
                                                    {{ method_field('delete') }}
                                                    <button type="submit" class="btn btn-danger delete btn-sm">
                                                        <i class="fa fa-trash"></i>
                                                        @lang('site.delete')
                                                    </button>
                                                </form>
                                            @else

                                                <button class="btn btn-danger btn-sm disabled"> <i class="fa fa-trash"></i> @lang('site.delete')</button>
                                                
                                            @endif

                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        {{$categories->appends(request()->query())->links()}}
                    @else
                    @lang('site.no_data_found')
                        
                    @endif
                    
                    {{-- end of table --}}
                </div>
                {{-- end of box-body --}}
            </div>
        </section>
    </div>
@endsection