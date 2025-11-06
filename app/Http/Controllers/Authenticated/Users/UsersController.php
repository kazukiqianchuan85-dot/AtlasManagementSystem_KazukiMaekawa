<?php

namespace App\Http\Controllers\Authenticated\Users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gate;
use App\Models\Users\User;
use App\Models\Users\Subjects;
use App\Searchs\DisplayUsers;
use App\Searchs\SearchResultFactories;

class UsersController extends Controller
{

    public function showUsers(Request $request)
    {
        $keyword = $request->keyword;
        $category = $request->category;
        $updown = $request->updown;
        $gender = $request->sex;
        $role = $request->role;
        $subject_ids = $request->input('subject', []); // ← チェックされた科目IDの配列

        // ユーザー検索のベースクエリ
        $query = \App\Models\Users\User::query();

        // ①キーワード検索（名前 or ID）
        if (!empty($keyword)) {
            if ($category === 'id') {
                $query->where('id', 'like', "%{$keyword}%");
            } else {
                $query->where(function ($q) use ($keyword) {
                    $q->where('over_name', 'like', "%{$keyword}%")
                    ->orWhere('under_name', 'like', "%{$keyword}%")
                    ->orWhere('over_name_kana', 'like', "%{$keyword}%")
                    ->orWhere('under_name_kana', 'like', "%{$keyword}%");
                });
            }
        }

        // ②性別
        if (!empty($gender)) {
            $query->where('sex', $gender);
        }

        // ③権限
        if (!empty($role)) {
            $query->where('role', $role);
        }

        // ④選択科目（複数選択対応）
        if (!empty($subject_ids)) {
            $query->whereHas('subjects', function ($q) use ($subject_ids) {
                $q->whereIn('subjects.id', $subject_ids);
            });
        }

        // 並び替え（昇順 / 降順）
        $order = $updown === 'DESC' ? 'DESC' : 'ASC';
        $query->orderBy('id', $order);

        // 実行
        $users = $query->with('subjects')->get();

        $subjects = \App\Models\Users\Subjects::all();

        return view('authenticated.users.search', compact('users', 'subjects'));
    }


    public function userProfile($id){
        $user = User::with('subjects')->findOrFail($id);
        $subject_lists = Subjects::all();
        return view('authenticated.users.profile', compact('user', 'subject_lists'));
    }

    public function userEdit(Request $request){
        $user = User::findOrFail($request->user_id);
        $user->subjects()->sync($request->subjects);
        return redirect()->route('user.profile', ['id' => $request->user_id]);
    }
}
