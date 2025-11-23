<?php

namespace App\Http\Controllers\Authenticated\Calendar\General;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Calendars\General\CalendarView;
use App\Models\Calendars\ReserveSettings;
use App\Models\Calendars\Calendar;
use App\Models\USers\User;
use Auth;
use DB;

class CalendarController extends Controller
{
    public function show(){
        $calendar = new CalendarView(time());
        return view('authenticated.calendar.general.calendar', compact('calendar'));
    }

    public function reserve(Request $request){
        DB::beginTransaction();
        try{
            $getPart = $request->getPart;
            $getDate = $request->getData;

            $reserveDays = [];

            foreach ($getDate as $index => $date) {
                if (isset($getPart[$index]) && !empty($getPart[$index])) {
                    $reserveDays[$date] = $getPart[$index];
                }
            }

            foreach ($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)
                                                ->where('setting_part', $value)
                                                ->first();

                if ($reserve_settings) {
                    $reserve_settings->decrement('limit_users');
                    $reserve_settings->users()->attach(Auth::id());
                }
            }

            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
            throw $e;
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

}
