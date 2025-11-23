<x-guest-layout>

<style>
  .error-message {
    color: red;
    font-size: 12px;
    margin-bottom: 2px;
  }
</style>

<div class="register-wrapper">
  <div class="register-box">

    <form action="{{ route('registerPost') }}" method="POST">
      @csrf

      {{-- ==================================
           姓・名
      ================================== --}}
      <div class="d-flex" style="justify-content: space-between;">

        {{-- 姓 --}}
        <div style="width:46%;">
          @error('over_name')
            <p class="error-message">{{ $message }}</p>
          @enderror
          <label class="register-label">姓</label>
          <div class="register-input-wrap">
            <input type="text" class="register-input" name="over_name" value="{{ old('over_name') }}">
          </div>
        </div>

        {{-- 名 --}}
        <div style="width:46%;">
          @error('under_name')
            <p class="error-message">{{ $message }}</p>
          @enderror
          <label class="register-label">名</label>
          <div class="register-input-wrap">
            <input type="text" class="register-input" name="under_name" value="{{ old('under_name') }}">
          </div>
        </div>

      </div>

      {{-- ==================================
           セイ・メイ
      ================================== --}}
      <div class="d-flex mt-4" style="justify-content: space-between;">

        {{-- セイ --}}
        <div style="width:46%;">
          @error('over_name_kana')
            <p class="error-message">{{ $message }}</p>
          @enderror
          <label class="register-label">セイ</label>
          <div class="register-input-wrap">
            <input type="text" class="register-input" name="over_name_kana" value="{{ old('over_name_kana') }}">
          </div>
        </div>

        {{-- メイ --}}
        <div style="width:46%;">
          @error('under_name_kana')
            <p class="error-message">{{ $message }}</p>
          @enderror
          <label class="register-label">メイ</label>
          <div class="register-input-wrap">
            <input type="text" class="register-input" name="under_name_kana" value="{{ old('under_name_kana') }}">
          </div>
        </div>

      </div>

      {{-- ==================================
           メールアドレス
      ================================== --}}
      <div class="mt-4">
        @error('mail_address')
          <p class="error-message">{{ $message }}</p>
        @enderror
        <label class="register-label">メールアドレス</label>
        <div class="register-input-wrap">
          <input type="text" class="register-input" name="mail_address" value="{{ old('mail_address') }}">
        </div>
      </div>

      {{-- ==================================
           性別
      ================================== --}}
      <div class="mt-4 register-radio-area">
        @error('sex')
          <p class="error-message">{{ $message }}</p>
        @enderror

        <label class="register-label">性別</label>

        <input type="radio" name="sex" value="1" {{ old('sex') == 1 ? 'checked' : '' }}> 男性
        <input type="radio" name="sex" value="2" {{ old('sex') == 2 ? 'checked' : '' }}> 女性
        <input type="radio" name="sex" value="3" {{ old('sex') == 3 ? 'checked' : '' }}> その他
      </div>

      {{-- ==================================
           生年月日
      ================================== --}}
      <div class="mt-4 register-select-area">
        @error('old_year')
          <p class="error-message">{{ $message }}</p>
        @enderror

        <label class="register-label">生年月日</label>

        <select name="old_year">
          <option value="none">----</option>
          @for($y = 1985; $y <= 2010; $y++)
            <option value="{{ $y }}" {{ old('old_year') == $y ? 'selected' : '' }}>{{ $y }}</option>
          @endfor
        </select> 年

        <select name="old_month">
          <option value="none">----</option>
          @for($m = 1; $m <= 12; $m++)
            <option value="{{ sprintf('%02d', $m) }}" {{ old('old_month') == sprintf('%02d', $m) ? 'selected' : '' }}>
              {{ $m }}
            </option>
          @endfor
        </select> 月

        <select name="old_day">
          <option value="none">----</option>
          @for($d = 1; $d <= 31; $d++)
            <option value="{{ sprintf('%02d', $d) }}" {{ old('old_day') == sprintf('%02d', $d) ? 'selected' : '' }}>
              {{ $d }}
            </option>
          @endfor
        </select> 日
      </div>

      {{-- ==================================
           役職
      ================================== --}}
      <div class="mt-4 register-radio-area">
        @error('role')
          <p class="error-message">{{ $message }}</p>
        @enderror

        <label class="register-label">役職</label>

        <input type="radio" name="role" value="1"> 教師(国語)
        <input type="radio" name="role" value="2"> 教師(数学)
        <input type="radio" name="role" value="3"> 教師(英語)
        <input type="radio" name="role" value="4" class="other_role"> 生徒
      </div>

      {{-- ==================================
           科目（生徒のみ表示）
      ================================== --}}
      <div class="select_teacher d-none mt-3">
        <label class="register-label">選択科目</label>

        @foreach($subjects as $subject)
          <div>
            <input type="checkbox" name="subject[]" value="{{ $subject->id }}">
            <label>{{ $subject->subject }}</label>
          </div>
        @endforeach
      </div>

      {{-- ==================================
           パスワード
      ================================== --}}
      <div class="mt-4">
        @error('password')
          <p class="error-message">{{ $message }}</p>
        @enderror
        <label class="register-label">パスワード</label>
        <div class="register-input-wrap">
          <input type="password" class="register-input" name="password">
        </div>
      </div>

      {{-- ==================================
           確認用パスワード
      ================================== --}}
      <div class="mt-4">
        <label class="register-label">確認用パスワード</label>
        <div class="register-input-wrap">
          <input type="password" class="register-input" name="password_confirmation">
        </div>
      </div>

      {{-- ==================================
           登録ボタン
      ================================== --}}
      <div class="mt-5 text-right w-100">
          <input type="submit" class="btn btn-primary register_btn" value="新規登録">
      </div>

      {{-- ==================================
           ログインへ
      ================================== --}}
      <a href="{{ route('loginView') }}" class="register-login-link">ログインはこちら</a>

    </form>

  </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(function() {
  $('.role, .other_role').on('change', function() {
    const roleValue = $('input[name="role"]:checked').val();
    if (roleValue == 4) {
      $('.select_teacher').removeClass('d-none');
    } else {
      $('.select_teacher').addClass('d-none');
      $('.select_teacher input[type="checkbox"]').prop('checked', false);
    }
  });
});
</script>

</x-guest-layout>
