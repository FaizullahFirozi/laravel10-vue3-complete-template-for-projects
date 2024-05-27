<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
  public function index()
  {
    return Activity::latest()->limit(6)->get();
    // return Activity::orderBy('id', 'desc')->get(); // BOTH ARE SAME
  }
}
