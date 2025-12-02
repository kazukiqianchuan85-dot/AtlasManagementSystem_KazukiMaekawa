<?php
namespace App\Calendars\Admin;
use Carbon\Carbon;

class CalendarSettingView{
  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  public function render(){
      $html = [];

      // ▼ カレンダー全体の白背景ラッパー
      $html[] = '<div class="calendar-wrapper" style="background:#fff; padding:20px; border-radius:10px; margin-bottom:40px;">';

      // ▼ タイトル
      $html[] = '<h2 class="calendar-title" style="text-align:center; margin-bottom:20px;">'
                  .$this->getTitle().
                '</h2>';

      // ▼ カレンダー本体
      $html[] = '<div class="calendar text-center">';
      $html[] = '<table class="table m-auto border adjust-table">';
      $html[] = '<thead>';
      $html[] = '<tr>';
      $html[] = '<th class="border">月</th>';
      $html[] = '<th class="border">火</th>';
      $html[] = '<th class="border">水</th>';
      $html[] = '<th class="border">木</th>';
      $html[] = '<th class="border">金</th>';
      $html[] = '<th class="border day-sat" style="color:#0000FF;">土</th>';
      $html[] = '<th class="border day-sun" style="color:#FF0000;">日</th>';
      $html[] = '</tr>';
      $html[] = '</thead>';
      $html[] = '<tbody>';

      $weeks = $this->getWeeks();

      foreach($weeks as $week){
          $html[] = '<tr class="'.$week->getClassName().'">';
          $days = $week->getDays();

          foreach($days as $day){
              $startDay = $this->carbon->format("Y-m-01");
              $toDay = $this->carbon->format("Y-m-d");

              if ($day->everyDay() < $toDay) {
                  $html[] = '<td class="past-day border '.$day->getClassName().'">';
              } else {
                  $html[] = '<td class="border '.$day->getClassName().'">';
              }


              $html[] = $day->render();
              $html[] = '<div class="adjust-area">';

              if($day->everyDay()){
                  if($startDay <= $day->everyDay() && $toDay >= $day->everyDay()){
                      $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" disabled value="'.$day->onePartFrame($day->everyDay()).'"></p>';
                      $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" disabled value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
                      $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" disabled value="'.$day->threePartFrame($day->everyDay()).'"></p>';
                  }else{
                      $html[] = '<p class="d-flex m-0 p-0">1部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][1]" type="text" form="reserveSetting" value="'.$day->onePartFrame($day->everyDay()).'"></p>';
                      $html[] = '<p class="d-flex m-0 p-0">2部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][2]" type="text" form="reserveSetting" value="'.$day->twoPartFrame($day->everyDay()).'"></p>';
                      $html[] = '<p class="d-flex m-0 p-0">3部<input class="w-25" style="height:20px;" name="reserve_day['.$day->everyDay().'][3]" type="text" form="reserveSetting" value="'.$day->threePartFrame($day->everyDay()).'"></p>';
                  }
              }

              $html[] = '</div>';
              $html[] = '</td>';
          }

          $html[] = '</tr>';
      }

      $html[] = '</tbody>';
      $html[] = '</table>';
      $html[] = '</div>'; // .calendar

      $html[] = '<div style="text-align:right; margin-top:30px;">';
      $html[] = '<input type="submit" class="btn btn-primary" value="登録" form="reserveSetting" onclick="return confirm(\'登録してよろしいですか？\')">';
      $html[] = '</div>';

      $html[] = '<form action="'.route('calendar.admin.update').'" method="post" id="reserveSetting">';
      $html[] = csrf_field();
      $html[] = '</form>';

      $html[] = '</div>'; // .calendar-wrapper

      return implode("", $html);
  }


  protected function getWeeks(){
    $weeks = [];
    $firstDay = $this->carbon->copy()->firstOfMonth();
    $lastDay = $this->carbon->copy()->lastOfMonth();
    $week = new CalendarWeek($firstDay->copy());
    $weeks[] = $week;

    $tmpDay = $firstDay->copy()->addDay(7)->startOfWeek();
    while($tmpDay->lte($lastDay)){
      $week = new CalendarWeek($tmpDay, count($weeks));
      $weeks[] = $week;
      $tmpDay->addDay(7);
    }
    return $weeks;
  }
}
