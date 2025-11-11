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
            $reserveDays = array_filter(array_combine($getDate, $getPart));
            foreach($reserveDays as $key => $value){
                $reserve_settings = ReserveSettings::where('setting_reserve', $key)->where('setting_part', $value)->first();
                $reserve_settings->decrement('limit_users');
                $reserve_settings->users()->attach(Auth::id());
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollback();
        }
        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }

    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            $delete_date = $request->input('delete_date');

            $reserve = Auth::user()->reserveSettings()
                ->where('setting_reserve', $delete_date)
                ->first();

            if ($reserve) {
                $setting = ReserveSettings::where('setting_reserve', $reserve->setting_reserve)
                    ->where('setting_part', $reserve->setting_part)
                    ->first();

                if ($setting) {
                    $setting->increment('limit_users');
                }

                Auth::user()->reserveSettings()->detach($reserve->id);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return redirect()->route('calendar.general.show', ['user_id' => Auth::id()]);
    }
}
