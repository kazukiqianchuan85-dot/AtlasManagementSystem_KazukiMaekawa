<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true; // 認可を常に許可
    }

    public function rules()
    {
        return [
            // 氏名
            'over_name' => ['required', 'string', 'max:10'],
            'under_name' => ['required', 'string', 'max:10'],

            // カナ（カタカナ限定）
            'over_name_kana' => ['required', 'string', 'max:30', 'regex:/^[ァ-ヴー]+$/u'],
            'under_name_kana' => ['required', 'string', 'max:30', 'regex:/^[ァ-ヴー]+$/u'],

            // メール
            'mail_address' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users,mail_address'
            ],

            // 性別：1,2,3のみ
            'sex' => ['required', Rule::in([1, 2, 3])],

            // 生年月日（年・月・日が揃っていて、2000/1/1～今日までの有効日付）
            'old_year' => ['required'],
            'old_month' => ['required'],
            'old_day' => ['required', function ($attribute, $value, $fail) {
                $year = request()->input('old_year');
                $month = request()->input('old_month');
                $day = request()->input('old_day');

                // 不正な日付チェック
                if (!checkdate((int)$month, (int)$day, (int)$year)) {
                    $fail('存在しない日付です。');
                    return;
                }

                $birth = Carbon::createFromDate($year, $month, $day);
                $min = Carbon::create(2000, 1, 1);
                $max = Carbon::today();

                if ($birth->lt($min) || $birth->gt($max)) {
                    $fail('生年月日は2000年1月1日から本日までの範囲で入力してください。');
                }
            }],

            // 役職
            'role' => ['required', Rule::in([1, 2, 3, 4])],

            // パスワード
            'password' => ['required', 'string', 'min:8', 'max:30', 'confirmed'],
        ];
    }

    public function messages()
    {
        return [
            'over_name.required' => '姓を入力してください。',
            'over_name.string' => '姓は文字列で入力してください。',
            'over_name.max' => '姓は10文字以内で入力してください。',

            'under_name.required' => '名を入力してください。',
            'under_name.string' => '名は文字列で入力してください。',
            'under_name.max' => '名は10文字以内で入力してください。',

            'over_name_kana.required' => 'セイを入力してください。',
            'over_name_kana.string' => 'セイは文字列で入力してください。',
            'over_name_kana.max' => 'セイは30文字以内で入力してください。',
            'over_name_kana.regex' => 'セイは全角カタカナで入力してください。',

            'under_name_kana.required' => 'メイを入力してください。',
            'under_name_kana.string' => 'メイは文字列で入力してください。',
            'under_name_kana.max' => 'メイは30文字以内で入力してください。',
            'under_name_kana.regex' => 'メイは全角カタカナで入力してください。',

            'mail_address.required' => 'メールアドレスを入力してください。',
            'mail_address.email' => '有効なメールアドレスを入力してください。',
            'mail_address.max' => 'メールアドレスは100文字以内で入力してください。',
            'mail_address.unique' => 'このメールアドレスはすでに登録されています。',

            'sex.required' => '性別を選択してください。',
            'sex.in' => '性別の選択が正しくありません。',

            'old_year.required' => '生年月日の年を選択してください。',
            'old_month.required' => '生年月日の月を選択してください。',
            'old_day.required' => '生年月日の日を選択してください。',

            'role.required' => '役職を選択してください。',
            'role.in' => '役職の選択が正しくありません。',

            'password.required' => 'パスワードを入力してください。',
            'password.min' => 'パスワードは8文字以上で入力してください。',
            'password.max' => 'パスワードは30文字以内で入力してください。',
            'password.confirmed' => '確認用パスワードが一致しません。',
        ];
    }
}
