<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use GuzzleHttp\Client;

Route::get('/', function () {
    $client = new Client();
    try{
        $response = $client->request('GET', 'https://www.googleapis.com/youtube/v3/playlists', [
            'query' => [
                'part' => 'contentDetails,snippet,player',
                'channelId' => 'UCW5YeuERMmlnqo4oq8vwUpg',
                'key' => 'AIzaSyD7gdMqj6-7WSwZKHEGT0U6vaGHEGiWr9Y',
                'maxResults'=>50
            ]
        ]);
        $responseBody = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
//        $iframes = collect($responseBody['items'])->pluck('player.embedHtml', 'snippet.title');
        dd(collect($responseBody));
//        return $iframes['Firebase Functions'];
    }
    catch (Exception $exception){
        dd($exception->getMessage());
    }
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/channel/{channel}/playlists', "HomeController@playlists")->name('playlists');

Route::post('/enroll', "HomeController@enroll")->name('enroll');

Route::get('/watch/{playlist}', "HomeController@watch")->name('watch');

Route::post('/checkAttendance', "HomeController@checkAttendace")->name('checkAttendance');
