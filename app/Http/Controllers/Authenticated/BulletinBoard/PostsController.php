<?php

namespace App\Http\Controllers\Authenticated\BulletinBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categories\MainCategory;
use App\Models\Categories\SubCategory;
use App\Models\Posts\Post;
use App\Models\Posts\PostComment;
use App\Models\Posts\Like;
use App\Models\Users\User;
use App\Http\Requests\BulletinBoard\PostFormRequest;
use App\Http\Requests\CommentRequest;
use Auth;

class PostsController extends Controller
{
    public function show(Request $request)
    {
        $categories = MainCategory::with('subCategories')->get();
        $like = new Like;
        $post_comment = new Post;

        $query = Post::with('user', 'postComments', 'likes', 'subCategories');

        if (!empty($request->keyword)) {
            $keyword = $request->keyword;

            $sub = SubCategory::where('sub_category', $keyword)->first();

            if ($sub) {
                $query->whereHas('subCategories', function ($q) use ($sub) {
                    $q->where('sub_categories.id', $sub->id);
                });
            } else {
                $query->where(function ($q) use ($keyword) {
                    $q->where('post_title', 'like', "%{$keyword}%")
                    ->orWhere('post', 'like', "%{$keyword}%");
                });
            }
        }

        if (!empty($request->category_word)) {
            $query->whereHas('subCategories', function ($q) use ($request) {
                $q->where('sub_categories.id', $request->category_word);
            });
        }

        // いいねした投稿
        if ($request->like_posts) {
            $likes = Auth::user()->likePostId()->get('like_post_id');
            $query->whereIn('id', $likes);
        }

        // 自分の投稿
        if ($request->my_posts) {
            $query->where('user_id', Auth::id());
        }

        $posts = $query->get();

        return view('authenticated.bulletinboard.posts', compact('posts', 'categories', 'like', 'post_comment'));
    }

    public function postDetail($post_id){
        $post = Post::with('user', 'postComments', 'likes')->findOrFail($post_id);
        return view('authenticated.bulletinboard.post_detail', compact('post'));
    }

    public function postInput(){
        $main_categories = MainCategory::with('subCategories')->get();
        return view('authenticated.bulletinboard.post_create', compact('main_categories'));
    }

    public function postCreate(PostFormRequest $request)
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);

        // サブカテゴリーを紐づけ（多対多）
        $post->subCategories()->attach($request->post_category_id);

        return redirect()->route('post.show')->with('success', '投稿を作成しました。');
    }


    // public function postEdit(Request $request){
    //     Post::where('id', $request->post_id)->update([
    //         'post_title' => $request->post_title,
    //         'post' => $request->post_body,
    //     ]);
    //     return redirect()->route('post.detail', ['id' => $request->post_id]);
    // }

    public function postEdit(PostFormRequest $request)
    {
        $post = Post::findOrFail($request->post_id);
        if ($post->user_id !== Auth::id()) {
            abort(403, 'この投稿を編集する権限がありません。');
        }
        $post->update([
            'post_title' => $request->post_title,
            'post' => $request->post_body,
        ]);
        return redirect()
            ->route('post.detail', ['id' => $post->id])
            ->with('success', '投稿を編集しました。');
    }

    // public function postDelete($id){
    //     Post::findOrFail($id)->delete();
    //     return redirect()->route('post.show');
    // }
        public function postDelete($id)
    {
        $post = Post::findOrFail($id);
        if ($post->user_id !== Auth::id()) {
            abort(403, 'この投稿を削除する権限がありません。');
        }
        $post->delete();
        return redirect()
            ->route('post.show')
            ->with('success', '投稿を削除しました。');
    }
    public function mainCategoryCreate(Request $request)
    {
        // バリデーション
        $request->validate([
            'main_category_name' => 'required|string|max:100|unique:main_categories,main_category',
        ], [
            'main_category_name.required' => 'メインカテゴリー名を入力してください。',
            'main_category_name.string' => 'メインカテゴリー名は文字列で入力してください。',
            'main_category_name.max' => 'メインカテゴリー名は100文字以内で入力してください。',
            'main_category_name.unique' => '同じ名前のメインカテゴリーは登録できません。',
        ]);

        // 登録処理
        MainCategory::create([
            'main_category' => $request->main_category_name,
        ]);

        return redirect()
            ->route('post.input')
            ->with('success', 'メインカテゴリーを追加しました。');
    }

    public function subCategoryCreate(Request $request)
    {
        $request->validate([
            'main_category_id' => 'required|exists:main_categories,id',
            'sub_category_name' => 'required|string|max:100',
        ], [
            'main_category_id.required' => 'メインカテゴリーを選択してください。',
            'main_category_id.exists' => '選択したメインカテゴリーが無効です。',
            'sub_category_name.required' => 'サブカテゴリー名を入力してください。',
            'sub_category_name.string' => 'サブカテゴリー名は文字列で入力してください。',
            'sub_category_name.max' => 'サブカテゴリー名は100文字以内で入力してください。',
        ]);

        \App\Models\Categories\SubCategory::create([
            'main_category_id' => $request->main_category_id,
            'sub_category' => $request->sub_category_name,
        ]);

        return redirect()->route('post.input')->with('success', 'サブカテゴリーを追加しました。');
    }

    // public function commentCreate(Request $request){
    //     PostComment::create([
    //         'post_id' => $request->post_id,
    //         'user_id' => Auth::id(),
    //         'comment' => $request->comment
    //     ]);
    //     return redirect()->route('post.detail', ['id' => $request->post_id]);
    // }
    public function commentCreate(CommentRequest $request)
    {
        PostComment::create([
            'post_id' => $request->post_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment
        ]);

        return redirect()->route('post.detail', ['id' => $request->post_id]);
    }

    public function myBulletinBoard(){
        $posts = Auth::user()->posts()->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_myself', compact('posts', 'like'));
    }

    public function likeBulletinBoard(){
        $like_post_id = Like::with('users')->where('like_user_id', Auth::id())->get('like_post_id')->toArray();
        $posts = Post::with('user')->whereIn('id', $like_post_id)->get();
        $like = new Like;
        return view('authenticated.bulletinboard.post_like', compact('posts', 'like'));
    }

    public function postLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->like_user_id = $user_id;
        $like->like_post_id = $post_id;
        $like->save();

        return response()->json();
    }

    public function postUnLike(Request $request){
        $user_id = Auth::id();
        $post_id = $request->post_id;

        $like = new Like;

        $like->where('like_user_id', $user_id)
             ->where('like_post_id', $post_id)
             ->delete();

        return response()->json();
    }
}
