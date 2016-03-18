<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
    Route::get('/', function () {
        return view('home');
    });
    Route::get('/200', function () {
        return view('success');
    });
    Route::get('/token', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'user' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withInput()
                ->withErrors($validator);
        }
        //change the server address and workspace to match your system:
        $pmServer = 'http://pm3local';
        $pmWorkspace = 'workflow';
        $clientId = $request->client_id;
        $clientSecret = $request->client_secret;
        $username = $request->user;
        $password = $request->password;

        $postParams = array(
            'grant_type' => 'password',
            'scope' => '*',       //set to 'view_process' if not changing the process
            'client_id' => $clientId,
            'client_secret' => $clientSecret,
            'username' => $username,
            'password' => $password
        );

        $ch = curl_init("$pmServer/$pmWorkspace/oauth2/token");
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postParams);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $oToken = json_decode(curl_exec($ch));
        $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpStatus != 200) {
            return redirect('/')
                ->withInput()
                ->withErrors("Error in HTTP status code: $httpStatus\n");
//        print "Error in HTTP status code: $httpStatus\n";
//        return null;
        } elseif (isset($oToken->error)) {
            print "Error logging into $pmServer:\n" .
                "Error:       {$oToken->error}\n" .
                "Description: {$oToken->error_description}\n";
        } else {
            //At this point $oToken->access_token can be used to call REST endpoints.

            //If planning to use the access_token later, either save the access_token
            //and refresh_token as cookies or save them to a file in a secure location.

            //If saving them as cookies:
            setcookie("access_token", $oToken->access_token, time() + 86400);
            setcookie("refresh_token", $oToken->refresh_token); //refresh token doesn't expire
            setcookie("client_id", $clientId);
            setcookie("client_secret", $clientSecret);

            //If saving to a file:
            //file_put_contents("/secure/location/oauthAccess.json", json_encode($tokenData));
        }

//        return json_encode($oToken);
        //if all Ok
    return redirect('/200')->with('status',json_encode($oToken));

    });
});
