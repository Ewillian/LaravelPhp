@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            @foreach ($posts as $postsUser)
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ $postsUser->titre }}</div>

                        <div class="card-body">
                            @if (session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif
                            {{ $postsUser->contenu }}
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    </div>
@endsection
