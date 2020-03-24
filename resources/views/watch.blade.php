@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <div class="align-self-center">
                            {{$playlist->title}}
                        </div>
                        <div>
                            @if(auth()->check() && $playlist->enrolled)
                                <button {{$playlist->attended ? "disabled" : null}} class="btn btn-primary d-flex" onclick="attend.bind(this).call()">
                                    {{$playlist->attended ? "Attendee: ".auth()->user()->name : "Attend"}}
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body d-flex justify-content-center" id="player">
                        {!! $playlist->player !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("scripts")
    <script src="https://pixlcore.com/demos/webcamjs/webcam.min.js"></script>
    <script>
        $(function () {
            let iframe = $("iframe");
            iframe.attr('width', iframe.attr('width') * 1.24).attr('height', iframe.attr('height') * 1.24);
        });
        function attend() {
            Webcam.set({
                width: 320/2,
                height: 240/2,
                dest_width: 640,
                dest_height: 480,
                image_format: 'jpeg',
                jpeg_quality: 90,
            });
            Webcam.attach("#camera_attach");
            $(this).text("Please Wait..").attr('disabled', true).append('<div class="spinner-border spinner-border-sm text-dark align-self-center ml-1"></div>');
            setTimeout(snapShot.bind(this), 5000);
        }
        function snapShot() {
            Webcam.snap(function (data_uri) {
                $.ajax({
                    method: 'POST',
                    url: '{{route('checkAttendance')}}',
                    data: {
                        playlist: '{{$playlist->id}}',
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        snapshot: data_uri,
                    },
                    success: function (data) {
                        alert(data.result);
                        if(!data.status){
                            setTimeout(3000, snapShot.bind(this));
                        }
                        else{
                            $(this).text(`Attendee: ${data.attendee}`);
                        }
                    }.bind(this),
                    error: function (error) {
                        setTimeout(3000, snapShot.bind(this));
                    }.bind(this),
                })
            }.bind(this));
        }
    </script>
@endsection
