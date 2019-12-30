<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Event;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Quantum\base\Events\UserCreated;
use Quantum\base\Services\MembershipService;

class UserImport extends Controller
{

    /**
     * @var MembershipService
     */
    private $membershipService;

    public function __construct(MembershipService $membershipService)
    {
        $this->membershipService = $membershipService;
    }

    public function index()
    {
        $file = storage_path('/app/users.csv');
        if(!file_exists($file)) abort(404);
        $handle = fopen($file,'r');
        while ( ($data = fgetcsv($handle) ) !== FALSE ) {
        $this->createUser($data);
        }
    }


    private function createUser($data)
    {
        $username = trim(preg_replace('/[^\da-z]/i', '', $data[0]));
        $email = trim($data[1]);
        $fname = trim($data[2]);
        $lname = trim($data[3]);
        $paidSub = trim($data[20]);

        if($username == '' || $email == '') return;

        $existing = User::where('email', $email)->orWhere('username', $username)->first();
        if($existing) return;

        $user = new User();
        $user->username = $username;
        $user->email = $email;
        $user->password = bcrypt(str_random(10));
        $user->status = 'active';
        $user->email_confirmed = true;
        $user->registered_at = config('app.name');
        $user->save();

        $user->profile()->create([
            'first_name' => $fname,
            'last_name' => $lname,
        ]);

        $user->roles()->sync([3]);

        if($paidSub != '')
        {
            $this->membershipService->addUserMembership(11, $user, false);
        }

        Event::fire(new UserCreated($user));
    }
}
