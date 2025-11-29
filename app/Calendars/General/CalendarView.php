<?php
namespace App\Calendars\General;

use Carbon\Carbon;
use Auth;

class CalendarView{

  private $carbon;

  function __construct($date){
    $this->carbon = new Carbon($date);
  }

  public function getTitle(){
    return $this->carbon->format('Y年n月');
  }

  function render(){
    $html = [];
    $html[] = '<div class="calendar-wrapper">';
    $html[] = '<h2 class="calendar-title">'.$this->getTitle().'</h2>';
    $html[] = '<div class="calendar text-center">';
    $html[] = '<table class="table m-auto border">';
    $html[] = '<thead>';
    $html[] = '<tr>';
    $html[] = '<th class="border">月</th>';
    $html[] = '<th class="border">火</th>';
    $html[] = '<th class="border">水</th>';
    $html[] = '<th class="border">木</th>';
    $html[] = '<th class="border">金</th>';
    $html[] = '<th class="border day-sat">土</th>';
    $html[] = '<th class="border day-sun">日</th>';
    $html[] = '</tr>';
    $html[] = '</thead>';
    $html[] = '<tbody>';

    $weeks = $this->getWeeks();

    foreach($weeks as $week){
      $html[] = '<tr class="'.$week->getClassName().'">';
      $days = $week->getDays();

      foreach($days as $day){
        $today = Carbon::today()->format('Y-m-d');
        $isPast = $day->everyDay() < $today;

        $isCurrentMonth = $day->isCurrentMonth($this->carbon->month);

        $tdClass = 'calendar-td '.$day->getClassName().($isPast ? ' bg-light' : '');
        $html[] = '<td class="'.$tdClass.'">';

        $html[] = $day->render();

        if(!$isCurrentMonth){
            $html[] = '</td>';
            continue;
        }

        if(in_array($day->everyDay(), $day->authReserveDay())){

          $reservePart = $day->authReserveDate($day->everyDay())->first()->setting_part;
          $reservePart = "リモ{$reservePart}部";

          if($isPast){
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color:red;">'.$reservePart.'</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }else{
            $html[] = '<button type="submit" class="btn btn-danger p-0 w-75"
                        name="delete_date" style="font-size:12px" form="deleteParts"
                        value="'. $day->authReserveDate($day->everyDay())->first()->setting_reserve .'">'.
                        $reservePart .'</button>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }

        }else{
          if($isPast){
            $html[] = '<p class="m-auto p-0 w-75" style="font-size:12px; color:#999;">受付終了</p>';
            $html[] = '<input type="hidden" name="getPart[]" value="" form="reserveParts">';
          }else{
            $html[] = $day->selectPart($day->everyDay());
          }
        }

        $html[] = '<input type="hidden" name="getData[]" value="'.$day->everyDay().'" form="reserveParts">';
        $html[] = '</td>';
      }

      $html[] = '</tr>';
    }

    $html[] = '</tbody>';
    $html[] = '</table>';
    $html[] = '</div>';
    $html[] = '</div>';
    $html[] = '<form action="/reserve/calendar" method="post" id="reserveParts">'.csrf_field().'</form>';
    $html[] = '<form action="/delete/calendar" method="post" id="deleteParts">'.csrf_field().'</form>';

    return implode('', $html);
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
