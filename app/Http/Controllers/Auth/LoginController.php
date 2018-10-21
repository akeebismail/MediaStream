<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\WelcomeToKibb;
use App\PhotoTools;
use App\Traits\ApiAuthentication;
use App\User;
use Faker\Factory;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use WhichBrowser\Parser;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers, PhotoTools, ApiAuthentication;

    protected $username = 'username';

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($request->expectsJson()){
            return res(200, 'Logged in successfully');
        }
    }


    public function redirectToGoogle(){
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try{
            $user = Socialite::driver('google')->user();
        }catch (\Exception $e){
            return redirect('/');
        }
    }

    private function findOrCreateUser($providerUser)
    {
        if ($authUser = User::query()->where('email', $providerUser->email)->first()){
            return $authUser;
        }

        $user = User::query()->create([
           'username' => $providerUser,
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
            'confirmed' => 1,
            'password'  => bcrypt(str_random(20)),
            'avatar'    => $this->downloadImg($providerUser->getAvatar(), 'users/avatars'),
            'settings' => [
                'notify_submissions_replied' => true,
                'notify_comments_replied'   => true,
                'notify_mentions'           => true
            ],
            'info' => [
                'website' => null,
                'twitter'   => null
            ]
        ]);

        //\Mail::to($user->email)->queue(new WelcomeToKibb($user->username));
        $this->storeInRedis($user);
        $user_agent_parser = new Parser($_SERVER['HTTP_USER_AGENT']);
        \App\Actibity::create([
            'subject_id'           => $user->id,
            'ip_address'           => $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0',
            'user_agent'           => $_SERVER['HTTP_USER_AGENT'] ?? 'unknown',
            'country'              => $_SERVER['HTTP_CF_IPCOUNTRY'] ?? 'unknown',
            'device'               => $user_agent_parser->device->model ?? 'unknown',
            'os'                   => $user_agent_parser->os->toString() ?? 'unknown',
            'browser_name'         => $user_agent_parser->browser->name ?? 'unknown',
            'browser_version'      => $user_agent_parser->browser->version->toString() ?? 'unknown',
            'subject_type'         => 'App\User',
            'name'                 => 'created_user',
            'user_id'              => $user->id,
        ]);

        $this->redirectTo = '/discover-channels?newbie=1&sidebar=0';

        return $user;
    }

    protected function generateUsername($providerUser)
    {
        if ($nickname = $providerUser->getNickName()){
            if ($this->isUsernameInValidFormat($nickname)){
                return $this->makeSureUsernameIsUnique($nickname);
            }
            if ($this->isUsernameInValidFormat($nickname = str_slug($nickname,'.'))){
                return $this->makeSureUsernameIsUnique($nickname);
            }
        }
        if ($name = $providerUser->getUserName()){
            if ($this->isUsernameInValidFormat($name)){
                return $this->makeSureUsernameIsUnique($name);
            }

            if ($this->isUsernameInValidFormat($name = str_slug($name, '.'))){
                return $this->makeSureUsernameIsUnique($name);
            }
        }

        $email = $providerUser->getEmail();
        $parts = explode('@', $email);
        $emailUsername = $parts[0];
        if ($this->isUsernameInValidFormat($emailUsername)){
            return $this->makeSureUsernameIsUnique($emailUsername);
        }

        $faker = Factory::create();
        $fakerUsername = $faker->userName;
        if ($this->isUsernameInValidFormat($fakerUsername)){
            return $this->makeSureUsernameIsUnique($fakerUsername);
        }

        return $this->makeSureUsernameIsUnique(str_random(8));
    }

    public function makeSureUsernameIsUnique($username)
    {
        if (!User::query()->where('username', $username)->exists()){
            return $username;
        }

        $usernameNumber = 1;
        $newUsername = $username;
        $users = User::query()->where('username','LIKE', $this->username.'%')->get();
        while ($users->contains($newUsername)){
            $newUsername = $username.$usernameNumber;
            $usernameNumber++;
        }

        return $newUsername;
    }

    protected function isUsernameInValidFormat($username)
    {
        return preg_match('/^[A-Za-z0-9\._]+$/i', $username);
    }

    public function username()
    {
        $username = request()->input('username');
        $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        \request()->merge([$field => $username]);

        return $field;
    }

}

