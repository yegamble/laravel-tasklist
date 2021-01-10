@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div id="app">
                <v-app>
                    <task-list :tasks="{{Auth::user()->tasks ?? '[]'}}"></task-list>
                </v-app>
            </div>
{{--            <div class="card">--}}
{{--                <div class="card-header">{{ __('Your Tasks') }}</div>--}}

{{--                <div id="app">--}}
{{--                    <v-app>--}}
{{--                        <task-list :tasks="{{Auth::user()->tasks}}"></task-list>--}}
{{--                    </v-app>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </div>
</div>
@endsection
