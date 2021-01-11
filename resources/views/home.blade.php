@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            @guest

                @include('auth.login')

            @else
            <div class="card">
                <div id="app">
                    <v-app>
                        <task-list :tasks="{{Auth::user()->tasks ?? '[]'}}"></task-list>
                    </v-app>
                </div>
            </div>

            @endguest
        </div>
    </div>
</div>
@endsection
