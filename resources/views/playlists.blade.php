@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Available Playlists</h2>
        @forelse($playlists as $playlist)
            <div class="row mt-3">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            {{$playlist->title}}
                        </div>
                        <div class="card-body row">
                            <div class="col-md-2">
                                <img src="{{$playlist->thumbnail}}" alt="{{$playlist->title}}" class="img-thumbnail">
                            </div>
                            <div class="col-md-10">
                                <p class="mt-2">
                                    {{strlen($playlist->description) > 0 ? $playlist->description : "No Description"}}
                                </p>
                                <div class="row">
                                    <button id="enroll_{{$playlist->id}}" {{$playlist->enrolled ? "disabled"  : null}}
                                    class="btn btn-primary d-block mr-2" onclick="enroll('{{$playlist->id}}')">
                                    {{$playlist->enrolled ? "Enrolled"  : "Enroll"}}
                                    </button>

                                    <button class="btn btn-primary d-inline"
                                            onclick="window.location.href = '{{route('watch', $playlist->id)}}'">
                                        Watch
                                    </button>
                                </div>
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
                            No playlists Right Now!
                        </div>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
@endsection
@section("scripts")
    <script>
        function enroll(playlist) {
            $.ajax({
                method: 'post',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    playlist: parseInt(playlist)
                },
                url: '{{route('enroll')}}',
                success: function (data) {
                    console.log(data);
                    alert(data.message);
                    if (data.status == 1) {
                        $(`#enroll_${playlist}`).text('Enrolled').attr("disabled", true);
                    }
                    else if (data.status == 2) {
                        $(`#enroll_${playlist}`).text('Enroll').removeAttr("disabled");
                    }
                }
            });
        }
    </script>
@endsection
