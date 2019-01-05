@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm p-3 mb-5 bg-white rounded">
                <todo-component></todo-component>
            </div>
        </div>
    </div>
</div>
@endsection
