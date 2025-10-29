<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\System_setting;
use Brian2694\Toastr\Facades\Toastr;



class LanguageController extends Controller
{
    public function select_lng(Request $request)
    {
        session(['language' => strtolower($request->language)]);
        $language = sanitize($request->language);
        if (System_setting::where('key', 'language')->exists()) {
            System_setting::where('key', 'language')->update(['value' => $language]);
        } else {
            System_setting::create(['key' => 'language', 'value' => $language]);
        }
        Session::flash('success', get_phrase('Language updated successfully!'));
        return redirect()->back();
    }


  
}
