@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Available Channels</h2>
        @forelse($channels as $channel)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{$channel->title}}
                        </div>
                        <div class="card-body row">
                            <div class="col-md-1">
                                <img src="{{$channel->thumbnail}}" alt="{{$channel->title}}" class="img-thumbnail">
                            </div>
                            <div class="col-md-11">
                                <p class="mt-2">
                                    {{strlen($channel->description) > 0 ? $channel->description : "No Description"}}
                                </p>
                                <button class="btn btn-primary d-block" onclick="window.location.href = '{{route('playlists', $channel->id)}}'">
                                    Show Playlists
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">Sorry!</div>
                        <div class="card-body">
                            No Channels Right Now!
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
