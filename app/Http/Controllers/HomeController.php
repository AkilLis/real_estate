<?php

namespace App\Http\Controllers;

use App\Ad;
use App\Category;
use App\Contact_query;
use App\Country;
use App\Post;
use App\Slider;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(){
        $limit_premium_ads = get_option('number_of_premium_ads_in_home');
        $limit_regular_ads = get_option('number_of_free_ads_in_home');
        $limit_urgent_ads = get_option('number_of_urgent_ads_in_home');

        $sliders = Slider::all();
        $countries = Country::all();
        $premium_ads = Ad::activePremium()->with('city')->limit($limit_premium_ads)->orderBy('id', 'desc')->get();
        $regular_ads = Ad::activeRegular()->with('city')->limit($limit_regular_ads)->orderBy('id', 'desc')->get();
        $urgent_ads = Ad::activeUrgent()->with('city')->limit($limit_urgent_ads)->orderBy('id', 'desc')->take(4)->get();
        
        $posts = Post::whereType('post')->whereStatus('1')->limit(get_option('blog_post_amount_in_homepage'))->get();

        $youtube_ads = [
            'https://www.youtube.com/embed/BgHyoARZPyg',
            'https://www.youtube.com/embed/RsupKN7rT1o',
            'https://www.youtube.com/embed/BtpHVJwdv7M',
            'https://www.youtube.com/embed/q3pxLWSC1vo',
            'https://www.youtube.com/embed/LKHcR7pPqSQ',
            'https://www.youtube.com/embed/zBCbbXlVOhs',
            'https://www.youtube.com/embed/yEkxTNytPNM',
            'https://www.youtube.com/embed/wHYobkYPJ-A',
        ];

        //dd($youtube_ads[1]);

        return view($this->theme.'index', compact( 'premium_ads', 'regular_ads','urgent_ads', 'countries', 'sliders', 'posts', 'youtube_ads'));
    }

    public function contactUs(){
        $title = trans('app.contact_us');
        return view('theme.contact_us', compact('title'));
    }

    public function contactUsPost(Request $request){
        $rules = [
            'name'  => 'required',
            'email'  => 'required|email',
            'message'  => 'required',
        ];
        $this->validate($request, $rules);
        Contact_query::create(array_only($request->input(), ['name', 'email', 'message']));
        return redirect()->back()->with('success', trans('app.your_message_has_been_sent'));
    }
    
    public function contactMessages(){
        $title = trans('app.contact_messages');
        $contact_messages = Contact_query::select('name', 'email', 'message','created_at')->orderBy('id', 'desc')->get();

        return view('admin.contact_messages', compact('title', 'contact_messages'));
    }

    /**
     * Switch Language
     */
    public function switchLang($lang){
        session(['lang'=>$lang]);
        //return redirect(route('home'));
        return back();
    }

    /**
     * Reset Database
     */
    public function resetDatabase(){
        $database_location = base_path("database-backup/classified.sql");
        // Temporary variable, used to store current query
        $templine = '';
        // Read in entire file
        $lines = file($database_location);
        // Loop through each line
        foreach ($lines as $line)
        {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;
            // Add this line to the current segment
            $templine .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';')
            {
                // Perform the query
                DB::statement($templine);
                // Reset temp variable to empty
                $templine = '';
            }
        }
        $now_time = date("Y-m-d H:m:s");
        DB::table('ads')->update(['created_at' => $now_time, 'updated_at' => $now_time]);
    }


}
