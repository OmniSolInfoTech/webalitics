@extends('webalitics::boilerplate')

@section('title') Error running Webalitics @endsection

@section('content')
    
    <div class="row my-3">
        <div class="col-12 my-1 px-1">
            <div class="alert alert-danger h-100" role="alert">
            {{ $message }}
            </div>
        </div>
    </div>
@endsection