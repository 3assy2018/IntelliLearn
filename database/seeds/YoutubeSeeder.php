<?php

use App\Channel;
use GuzzleHttp\Client;
use Illuminate\Database\Seeder;

class YoutubeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $channels = config('yt.seeds_channels');
        $httpClient = new Client();
        $response = $httpClient->request('GET', 'https://www.googleapis.com/youtube/v3/channels', [
            'query' => [
                'part' => 'contentDetails,snippet',
                'id' => implode(',', $channels),
                'key' => 'AIzaSyD7gdMqj6-7WSwZKHEGT0U6vaGHEGiWr9Y',
                'maxResults'=>50
            ]
        ]);
        $responseBody = \GuzzleHttp\json_decode($response->getBody()->getContents(), true);
        $channels = array_map(function ($item){
            return [
                'title'=> $item['snippet']['title'],
                'description'=> $item['snippet']['description'] ?? "No Description",
                'thumbnail'=> $item['snippet']['thumbnails']['default']['url'],
                'code'=> $item['id'],
            ];
        }, $responseBody['items']);
        $channels = array_map([Channel::class, 'create'],$channels);
        $this->command->info("Channel Created Successfully");
        array_map(function ($channel) use ($httpClient){
            $playlistsResponse = $httpClient
                ->request('GET', 'https://www.googleapis.com/youtube/v3/playlists', [
                    'query' => [
                        'part' => 'contentDetails,snippet,player',
                        'channelId' => $channel->code,
                        'key' => 'AIzaSyD7gdMqj6-7WSwZKHEGT0U6vaGHEGiWr9Y',
                        'maxResults'=>50
                    ]
                ]);
            $responseBody = \GuzzleHttp\json_decode($playlistsResponse->getBody()->getContents(), true);
            $channelPlaylists = array_map(function ($item){
                return [
                    'title'=> $item['snippet']['title'],
                    'description'=> $item['snippet']['description'] ?? "No Description",
                    'code'=> $item['id'],
                    'thumbnail' => $item['snippet']['thumbnails']['default']['url'],
                    'player' => $item['player']['embedHtml'],
                ];
            },$responseBody['items']);
            $channelPlaylists = $channel->playlists()->createMany($channelPlaylists);
            $this->command->info($channel->title." Playlists Created Successfully");
            return $channelPlaylists;
        },$channels);
    }
}
