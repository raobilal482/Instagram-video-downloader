<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class InstagramDownloaderController extends Controller
{
    public function showForm()
    {
        return view('download');
    }

    public function download(Request $request)
    {
        $request->validate(['url' => 'required|url']);
        $instagramUrl = $request->input('url');

        try {
            $client = new Client();

            // Send POST request to Snapinsta's get-data.php
            $response = $client->post('https://snapinsta.app/get-data.php', [
                'form_params' => [
                    'url' => $instagramUrl,
                    'new' => '2',
                    'lang' => 'en',
                    'app' => ''
                ],
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:135.0) Gecko/20100101 Firefox/135.0',
                    'Referer' => 'https://snapinsta.app/',
                    'Accept' => '*/*',
                    'Accept-Encoding' => 'gzip, deflate, br, zstd',
                    'Origin' => 'https://snapinsta.app'
                ]
            ]);

            // Get the JSON response and decode it
            $json = $response->getBody()->getContents();
            $data = json_decode($json, true);

            // Extract the video URL from files[0].video_url
            $videoUrl = $data['files'][0]['video_url'] ?? null;

            if (!$videoUrl) {
                \Log::info('Snapinsta Response JSON:', ['json' => $json]);
                return redirect()->route('download.form')->with('error', 'Could not find video URL in Snapinsta response.');
            }

            // Download the video to your server
            $fileName = 'instagram_video_' . time() . '.mp4';
            $filePath = public_path('downloads/' . $fileName);
            if (!file_exists(public_path('downloads'))) {
                mkdir(public_path('downloads'), 0755, true);
            }
            $client->get($videoUrl, ['sink' => $filePath]);

            return response()->download($filePath, $fileName)->deleteFileAfterSend(true);
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            return redirect()->route('download.form')->with('error', 'Snapinsta error: ' . $e->getMessage());
        } catch (\Exception $e) {
            return redirect()->route('download.form')->with('error', 'General error: ' . $e->getMessage());
        }
    }
}
