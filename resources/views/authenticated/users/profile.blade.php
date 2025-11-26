<x-sidebar>
<div class="vh-100 border">
  <div class="top_area w-75 m-auto pt-5">
    <span>{{ $user->over_name }}</span><span>{{ $user->under_name }}さんのプロフィール</span>
    <div class="user_status p-3">

      <p>名前 : <span>{{ $user->over_name }}</span><span class="ml-1">{{ $user->under_name }}</span></p>
      <p>カナ : <span>{{ $user->over_name_kana }}</span><span class="ml-1">{{ $user->under_name_kana }}</span></p>
      <p>性別 : @if($user->sex == 1)<span>男</span>@else<span>女</span>@endif</p>
      <p>生年月日 : <span>{{ $user->birth_day }}</span></p>

      <div class="mb-2">選択科目 :
        @foreach($user->subjects as $subject)
          <span>{{ $subject->subject }}</span>
        @endforeach
      </div>

      @can('admin')
      <div>
        <span class="subject_edit_btn">選択科目の編集 ▼</span>

        <div class="subject_inner">
          <form action="{{ route('user.edit') }}" method="post">

            <div class="subject-items">
              @foreach($subject_lists as $subject_list)
              <label class="subject-item">
                <input type="checkbox" name="subjects[]" value="{{ $subject_list->id }}">
                {{ $subject_list->subject }}
              </label>
              @endforeach

              <button type="submit" class="btn btn-primary ml-3">登録</button>
            </div>

            <input type="hidden" name="user_id" value="{{ $user->id }}">
            {{ csrf_field() }}
          </form>
        </div>
      </div>
      @endcan

    </div>
  </div>
</div>

{{-- ▼ 開閉スクリプト --}}
<script>
  $(".subject_edit_btn").on("click", function () {
      $(this).toggleClass("open");

      if ($(this).hasClass("open")) {
        $(this).text("選択科目の編集 ▲");
      } else {
        $(this).text("選択科目の編集 ▼");
      }

      $(".subject_inner").slideToggle();
  });
</script>

</x-sidebar>
