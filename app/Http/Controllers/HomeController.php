<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Playlist;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Symfony\Component\Process\Process;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $channels = Channel::all();
        return view('home', compact('channels'));
    }

    public function playlists(Channel $channel)
    {
        $playlists = $channel->playlists;
        return view('playlists', compact('playlists'));
    }

    public function enroll(Request $request)
    {
        try {
            $status = $request->user()->playlists()->toggle($request->input('playlist'));
            $body = count($status['attached']) > 0
                ? ["message" => "You enrolled to the playlist successfully", "status" => 1]
                : (count($status['detached']) > 0
                    ? ["message" => "You left the course", "status" => 2]
                    : ["message" => "Nothing Happened", "status" => 3]);
            return response()->json($body);
        } catch (Exception $exception) {
            return response()->json(['message' => 'Something Went Error', 'status' => false]);
        }
    }

    public function watch(Playlist $playlist)
    {
        return view('watch', compact('playlist'));
    }

    public function checkAttendace(Request $request)
    {
        ini_set('max_execution_time', 0);
        $image = Image::make($request->snapshot);
        $path = "temp_attendance/" . time() . ".jpg";
        $savePath = public_path("/storage/" . $path);
        $image->save($savePath);
        $pythonFolder = __DIR__ . "../../../../IntelliLearn";
        $result = exec("python $pythonFolder/image_recognize.py --capture " . $path);
        if (strlen($result) == 32) {
            $user = User::where('profile_picture', 'like', $result . "%")->first();
            if ($user) {
                $body = [
                    'result' => $user->name . " Attended",
                    'attendee' => $user->name,
                    'status' => true,
                ];
                $user->playlists()->find($request->playlist)->pivot->attendance->update([
                    'attended' => 1
                ]);
                $moveFolder =
                    "/storage/photos/".md5($user->id)."/".str_replace("temp_attendance/", '', $path);
                if(count(scandir(public_path('/storage/photos/'.md5($user->id)))) < 12){
                    File::move($savePath, public_path($moveFolder));
                }
                else{
                    File::delete($savePath);
                }
            } else {
                $body = [
                    'result' => "Not Identified",
                    'attendee' => null,
                    'status' => false,
                ];
            }
        } else {
            $body = [
                'result' => "Not Identified",
                'attendee' => null,
                'status' => false,
            ];
        }
        return response()->json($body);
    }
}
