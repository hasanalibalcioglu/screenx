<?php

namespace App\Http\Controllers;



use App\Visit;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;

class ScreenshotController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function upload(Request $request) {
        if ($request->hasFile('image')) {
            $this->validate($request, [
               'image' => 'image|mimes:jpeg,png,jpg,gif,svg'
            ]);
            $image = $request->file('image');
            $name = time().'.'.$image->getClientOriginalExtension();
            $destinationPath = storage_path('/app/images');
            $image->move($destinationPath, $name);

            $data['link'] = route('show',['name' => $name]);
            return response()->json($data);
        }
        return "ERROR";
    }

    public function show($name) {
        $path = storage_path('app/images/' . $name);
        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $date = new DateTime;
        $date->modify('-1 minutes');
        $formatted_date = $date->format('Y-m-d H:i:s');

        $recentLog = Visit::where([
            'name' => $name,
            'ip' => $this->getIp()
        ])->where('created_at' , '>', $formatted_date)->first();
        if (!$recentLog){
            Visit::create([
                'name' => $name,
                'ip' => $this->getIp(),
            ]);
        }
        $response = response()->make($file, 200);
        $response->header("Content-Type", $type);

        return $response;
    }
    public function logs($name) {
        //$logs = Visit::where('ip' , '!=',$this->getIp())->get();
        $logs = Visit::where('name',$name)->orderBy('created_at','desc')->get();
        $result = array();
        foreach ($logs as $log) {
            $gip[$log['ip']][] = $log;
        }
        foreach ($gip as $key => $value){
            $ipData[$key] = $this->ipInfo($key);
        }

        $data['logs'] = $logs;
        $data['name'] = $name;
        $data['ipData'] = $ipData;
        return view('logs')->with($data);
    }

    public function getIp(){
        foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key){
            if (array_key_exists($key, $_SERVER) === true){
                foreach (explode(',', $_SERVER[$key]) as $ip){
                    $ip = trim($ip); // just to be safe
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false){
                        return $ip;
                    }
                }
            }
        }
    }

    public static function ipInfo($ip) {

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://freegeoip.app/json/".$ip,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "accept: application/json",
                "content-type: application/json"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        } else {
            return json_decode($response,true);
        }
    }
}
