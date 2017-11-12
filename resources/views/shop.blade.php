@extends('template.app')

@section('content')
<div class="container">

    @if( Session::has('message') )
    <div class="row animate-close">
        <div class="col-md-12">
            <div class="alert alert-success alert-dismissable">
                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                {{ Session::get('message') }}
            </div>
        </div>
    </div>
    @endif

    @if ($errors->any())
    <div class="row animate-close">
        <div class="col-md-12">    
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
        <div class="col-md-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><strong>Shop List</strong></div>
                <div class="panel-body">
                    <ul class="list-group">
                    @foreach( $list as $item )
                        <li class="list-group-item">                            
                            <a href="/shop/{{ $item->id }}">Shop {{ $item->id }}</a>
                        </li>
                    @endforeach
                    </ul>
                    <p><a href="/shop">Back to Home</a></p>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">Shop {{ $shop->id }}</div>
                <div class="panel-body">
                    <form class="form form-inline" method="POST" action="/shop/{{ $shop->id }}">                        
                        {{ method_field('PUT') }}
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="width">Width:
                                <input class='form-control' type="text" name="width" placeholder="ex:10" value="{{ $shop->width }}">
                            </label>
                        </div>
                        <div class="form-group">    
                            <label for="width">Height:
                                <input class='form-control' type="text" name="height" placeholder="ex:50" value="{{ $shop->height }}">
                            </label>
                        </div>                        
                        <button class="btn btn-primary" type="submit">Update</button>                        
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="panel panel-primary">
                <div class="panel-heading">Robot List</div>
                <div class="panel-body">
                    @if( count($shop->robots) < 1 )
                        <span>List is empty</span>
                    @else
                        <ul class="list-group">
                        @foreach( $shop->robots as $robot )
                            <li class="list-group-item">
                               <form class="form" method="POST" action="/shop/{{ $shop->id }}/robot/{{ $robot->id }}">
                                    {{ method_field('DELETE') }}
                                    {{ csrf_field() }}
                                    <div class="row">                         
                                        <div class="col-md-2">{{ $robot->name }}:</div>
                                        <div class="col-md-9">
                                            {{ $robot->position }}<br>{{ $robot->commands }}
                                        </div>                        
                                        <div class="col-md-1">
                                            <button type="submit" class="btn btn-danger btn-xs">
                                                <strong>x</strong>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </li>                        
                        @endforeach
                        </ul>
                    @endif                    
                </div>
                <div class="panel-footer">
                    <form class="form form-inline" method="POST" action="/shop/{{ $shop->id }}/robot">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="form-group">
                                <label for="width">X:
                                    <input class='form-control' type="text" name="x" placeholder="eq: (1,2,3,...)">
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="width">Heading:
                                    <input class='form-control' type="text" name="heading" placeholder="eq: (N,S,E,W)">
                                </label>
                            </div>                                                                                
                        </div>                        
                        <div class="form-group">
                            <div class="form-group">    
                                <label for="width">Y:
                                    <input class='form-control' type="text" name="y" placeholder="eq: (1,2,3,....)">
                                </label>
                            </div>
                            <div class="form-group">    
                                <label for="width">Commands:
                                    <input class='form-control' type="text" name="commands" placeholder="eq: LMLMLMLMM">
                                </label>
                            </div>                        
                            <button class="btn btn-primary" type="submit">Save</button>                        
                        </div>
                    </form>
                </div>
            </div>
                
            @if( count($shop->robots) > 0 )
            <form method="POST" action="/shop/{{ $shop->id }}/execute">
                {{ csrf_field() }}    
                <div class="form-group">
                    <button type="submit" class="btn btn-primary"><strong>Run Simulator</strong></button>
                </div>                
            </form>
            @endif

            @if( Session::has('simulation-result') )
            <strong>Simulation Result:</strong><br>
            <table class="table table-bordered" id="result">
                <tr>
                    @foreach(Session::get('simulation-result') as $result)
                        <th>{{ $result['robot'] }}</th>
                    @endforeach
                    <th>Status</th>
                </tr>
                <tr>
                    @foreach(Session::get('simulation-result') as $result)                                
                    <td>
                        <ul class="list-group">
                            @foreach($result['movements'] as $movement)
                            <li class="list-group-item">{{ $movement }}</li>
                            @endforeach
                        </ul>
                    </td>
                    @endforeach
                    <td>
                        <ul class="list-group">
                            @foreach(Session::get('simulation-status') as $status)
                            <li class="list-group-item">{{ $status }}</li>
                            @endforeach
                        </ul>
                    </td>                                
                </tr>
            </table>    
            @endif
                
        </div>
    </div>                    

</div>
@endsection
