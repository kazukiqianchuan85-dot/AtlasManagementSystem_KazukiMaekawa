<x-sidebar>

<div class="board_area w-100 d-flex">

    {{--========================================
        投稿一覧（左）
    ========================================--}}
    <div class="post_view w-75">

        @foreach($posts as $post)
        <div class="post_area">

            <p class="mb-1">
                <span>{{ $post->user->over_name }}</span>
                <span class="ml-2">{{ $post->user->under_name }}</span>さん
            </p>

            <p class="mt-1 mb-2">
                <a href="{{ route('post.detail', ['id' => $post->id]) }}">
                    {{ $post->post_title }}
                </a>
            </p>

            {{-- ▼ 投稿下段（サブカテゴリー & コメント/いいね） --}}
            <div class="post_bottom_area">

                {{-- 左：サブカテゴリ --}}
                <div class="post_tags">
                    @foreach($post->subCategories as $sub)
                        <span class="tag-badge">{{ $sub->sub_category }}</span>
                    @endforeach
                </div>

                {{-- 右：コメント & いいね --}}
                <div class="d-flex post_status">
                    <div class="mr-4">
                        <i class="fa fa-comment"></i>
                        <span>{{ $post->postComments->count() }}</span>
                    </div>

                    <div>
                        @if ($post->isLikedByUser())
                            <i class="fas fa-heart un_like_btn" post_id="{{ $post->id }}"></i>
                        @else
                            <i class="fas fa-heart like_btn" post_id="{{ $post->id }}"></i>
                        @endif
                        <span class="like_counts{{ $post->id }}">
                            {{ $post->likes->count() }}
                        </span>
                    </div>
                </div>

            </div>

        </div>
        @endforeach

    </div>


    {{--========================================
        右側（投稿ボタン・検索・カテゴリ）
    ========================================--}}
    <div class="other_area border w-25">
      <div class="m-4">

        <!-- 投稿ボタン -->
        <button onclick="location.href='{{ route('post.input') }}'" class="post-btn">
          投稿
        </button>

        <!-- 検索 -->
        <div class="search-box">
          <input type="text" placeholder="キーワードを検索" name="keyword" form="postSearchRequest">
          <input type="submit" value="検索" form="postSearchRequest">
        </div>

        <!-- いいね・自分の投稿 -->
        <div class="filter-btn-wrap">
          <input type="submit" name="like_posts" class="like-filter-btn" value="いいねした投稿" form="postSearchRequest">
          <input type="submit" name="my_posts" class="my-post-filter-btn" value="自分の投稿" form="postSearchRequest">
        </div>


        <p class="category-title mt-3 mb-2">カテゴリー検索</p>

        <!-- カテゴリー -->
        <ul class="mt-4">
          @foreach($categories as $category)
            <li class="main_categories" category_id="{{ $category->id }}">
              <strong>{{ $category->main_category }}</strong>
              <ul class="ml-3">
                @foreach($category->subCategories as $sub)
                  <li>
                    <form action="{{ route('post.show') }}" method="get" style="display:inline;">
                      <input type="hidden" name="category_word" value="{{ $sub->id }}">
                      <button type="submit" class="btn btn-link p-0 m-0 text-left">{{ $sub->sub_category }}</button>
                    </form>
                  </li>
                @endforeach
              </ul>
            </li>
          @endforeach
        </ul>

      </div>
    </div>



    <form action="{{ route('post.show') }}" method="get" id="postSearchRequest"></form>

</div>


{{-- ▼ カテゴリ開閉 JavaScript --}}
<script>
document.querySelectorAll('.main_categories').forEach(cat => {
    cat.addEventListener('click', () => {
        cat.classList.toggle('open');
    });
});
</script>

</x-sidebar>
