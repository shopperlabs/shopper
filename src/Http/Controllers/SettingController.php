<?php

namespace Shopper\Framework\Http\Controllers;

use Illuminate\Routing\Controller;

class SettingController extends Controller
{
  public function index()
  {
    return view('shopper::pages.settings.index');
  }
}
