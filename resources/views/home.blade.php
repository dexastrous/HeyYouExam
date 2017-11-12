@extends('template.app')

@section('content')
<div class="container">
    @if( Session::has('message') )
    <div class="row animate-close">
        <div class="col-md-8 col-md-offset-2">
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                {{ Session::get('message') }}
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="row animate-close">
        <div class="col-md-8 col-md-offset-2">    
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Create New Shop</div>
                <div class="panel-body">
                    <form class="form form-inline" method="POST" action="/shop">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="width">Width:
                                <input class='form-control' type="text" name="width" placeholder="ex:10">
                            </label>
                        </div>
                        <div class="form-group">    
                            <label for="width">Height:
                                <input class='form-control' type="text" name="height" placeholder="ex:50">
                            </label>
                        </div>                        
                        <button class="btn btn-primary" type="submit">Save</button>                        
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-primary">
                <div class="panel-heading">Shop List</div>
                <div class="panel-body">
                    @if( count($shops) < 1 )
                        <span>List is empty</span>
                    @else
                        <ul class="list-group">
                        @foreach( $shops as $shop )
                            <li class="list-group-item"> 
                                <form class="form" method="POST" action="/shop/{{ $shop->id }}">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <div class="btn-group pull-right">
                                        <button type="submit" class="btn btn-danger btn-xs">
                                            <strong>x</strong>
                                        </button>
                                    </div>
                                    <a href="/shop/{{ $shop->id }}">Shop {{ $shop->id }}</a>
                                </form>
                            </li>
                        @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>    

</div>
@endsection
