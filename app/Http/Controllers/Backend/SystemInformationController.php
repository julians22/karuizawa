<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Libraries\Api\Accurate\Oauth;
use Illuminate\Http\Request;

class SystemInformationController extends Controller
{
    public function index()
    {
        return view('backend.system-information.index');
    }

    public function reloadAuthCache(Oauth $oauth)
    {
        $oauth->makeSignature();
        return redirect()->route('admin.system-information.index')->withFlashSuccess(__('The authentication cache was successfully reloaded.'));
    }
}
