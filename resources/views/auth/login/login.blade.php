<x-guest-layout>
    <div class="login-wrapper">

        {{-- ロゴ --}}
        <div class="login-logo">
            <img src="{{ asset('image/atlas-black.png') }}" alt="Atlas Logo" class="login-logo-img">
        </div>

        {{-- ボックス --}}
        <div class="login-box">
            <form action="{{ route('loginPost') }}" method="POST">
                @csrf

                <label class="login-label">メールアドレス</label>
                <div class="login-input-wrap">
                    <input type="text" class="login-input" name="mail_address">
                </div>

                <label class="login-label mt-4">パスワード</label>
                <div class="login-input-wrap">
                    <input type="password" class="login-input" name="password">
                </div>

                <div class="text-right mt-4">
                    <input type="submit" class="login-btn" value="ログイン">
                </div>

                <div class="text-center mt-3">
                    <a href="{{ route('registerView') }}" class="login-register-link">新規登録はこちら</a>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>
