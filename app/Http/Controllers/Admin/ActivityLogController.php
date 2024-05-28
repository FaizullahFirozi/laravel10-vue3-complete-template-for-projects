<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
  public function index()
  {
    // return Activity::latest()->limit(6)->get();

    $perPage = request('per_page');
    $activity_logs = Activity::latest()->paginate($perPage);

    return response()->json($activity_logs);
    // return Activity::orderBy('id', 'desc')->get(); // BOTH ARE SAME
  }


  
  
  public function search()
  {

      $searchQuery = request('query', '');
      $perPage = request('per_page', 10);


      $activity_logs = Activity::query()
          ->where('log_name', 'like', "%{$searchQuery}%")
          ->orWhere('properties', 'like', "%{$searchQuery}%")
          ->orWhere('description', 'like', "%{$searchQuery}%")
          ->orWhere('id', 'like', "%{$searchQuery}%")
          ->orWhere('updated_at', 'like', "%{$searchQuery}%")
          ->orWhere('event', 'like', "%{$searchQuery}%")
          ->orderBy('id', 'desc')
          ->paginate($perPage);

      return response()->json($activity_logs);


  }
}
