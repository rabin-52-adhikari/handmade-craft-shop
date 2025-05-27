<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Settings;
use App\User;
use App\Rules\MatchOldPassword;
use Carbon\Carbon;
use Illuminate\Contracts\Session\Session as SessionSession;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
{
    public function index()
    {
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        //  return $data;
        return view('backend.index')->with('users', json_encode($array));
    }

    public function profile()
    {
        $profile = Auth()->user();
        // return $profile;
        return view('backend.users.profile')->with('profile', $profile);
    }

    public function profileUpdate(Request $request, $id)
    {
        // return $request->all();
        $user = User::findOrFail($id);
        $data = $request->all();
        if ($request->hasFile('photo')) {

            if ($user->photo && Storage::exists(str_replace('storage/', 'public/', $user->photo))) {
                Storage::delete(str_replace('storage/', 'public/', $user->photo));
            }
            $file = $request->file('photo');
            $fileName = 'Admin-Photo' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);
            $data['photo'] = '/storage/photos/' . $fileName;
        }
        $status = $user->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Successfully updated your profile');
        } else {
            request()->session()->flash('error', 'Please try again!');
        }
        return redirect()->back();
    }

    public function settings()
    {
        $data = Settings::first();
        return view('backend.setting')->with('data', $data);
    }

    public function settingsUpdate(Request $request)
    {
        // return $request->all();
        $this->validate($request, [
            'short_des' => 'required|string',
            'description' => 'required|string',
            'photo' => 'nullable',
            'logo' => 'nullable',
            'address' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);
        $data = $request->all();

        // return $data;
        $settings = Settings::first();
        if ($request->hasFile('photo')) {

            if ($settings->photo && Storage::exists(str_replace('storage/', 'public/', $settings->photo))) {
                Storage::delete(str_replace('storage/', 'public/', $settings->photo));
            }
            $file = $request->file('photo');
            $fileName = 'Admin-Photo' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);
            $data['photo'] = '/storage/photos/' . $fileName;
        }
        if ($request->hasFile('logo')) {

            if ($settings->logo && Storage::exists(str_replace('storage/', 'public/', $settings->logo))) {
                Storage::delete(str_replace('storage/', 'public/', $settings->logo));
            }

            $file = $request->file('logo');
            $fileName = 'logo' . '-' . time() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/photos', $fileName);
            $data['logo'] = '/storage/photos/' . $fileName;
        }

        $status = $settings->fill($data)->save();
        if ($status) {
            request()->session()->flash('success', 'Setting successfully updated');
        } else {
            request()->session()->flash('error', 'Please try again');
        }
        return redirect()->route('admin');
    }

    public function changePassword()
    {
        return view('backend.layouts.changePassword');
    }
    public function changPasswordStore(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'new_password' => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

        return redirect()->route('admin')->with('success', 'Password successfully changed');
    }

    // Pie chart
    public function userPieChart(Request $request)
    {
        // dd($request->all());
        $data = User::select(\DB::raw("COUNT(*) as count"), \DB::raw("DAYNAME(created_at) as day_name"), \DB::raw("DAY(created_at) as day"))
            ->where('created_at', '>', Carbon::today()->subDay(6))
            ->groupBy('day_name', 'day')
            ->orderBy('day')
            ->get();
        $array[] = ['Name', 'Number'];
        foreach ($data as $key => $value) {
            $array[++$key] = [$value->day_name, $value->count];
        }
        //  return $data;
        return view('backend.index')->with('course', json_encode($array));
    }

    // public function activity(){
    //     return Activity::all();
    //     $activity= Activity::all();
    //     return view('backend.layouts.activity')->with('activities',$activity);
    // }


    public function login()
    {
        return view('backend.admin.login');
    }
    public function loginSubmit(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $loginField = filter_var($request->get('email'), FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'phone'; // Use 'phone' as the field for phone numbers in your table

        $credentials = [
            $loginField => $request->get('email'), // Dynamically select email or phone
            'password' => $request->get('password'),
            'role' => 'admin' ,// Ensure user status is active
            'status' => 'active' // Ensure user status is active
        ];

        if (Auth::attempt($credentials)) {
            // Store user info in the session
            Session::put('user', Auth::user());
            $request->session()->flash('success', 'Successfully logged in');
            return redirect()->route('admin');
        } else {
            // Flash error message
            $request->session()->flash('error', 'Invalid email/phone number or password, please try again!');
            return redirect()->back();
        }
    }
}
