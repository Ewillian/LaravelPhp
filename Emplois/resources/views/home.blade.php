@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                @foreach($jobs as $job)
                    <div class="card">
                        <div class="card-header">
                            <a href="#">
                                {{ $job->titre }}
                            </a>
                            <cite title="Source Title" class="float-right">{{ $job->updated_at->format('d-m-Y H:m:i') }}</cite>
                        </div>

                        <div class="card-body">
                            {{ $job->contenu }}
                            <div style="margin-top: 15px;">
                                <div class="">
                                    <span class="float-right">{{ $job->companies }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>

                @endforeach

                {{ $jobs->links() }}

            </div>
        </div>
    </div>
@endsection
