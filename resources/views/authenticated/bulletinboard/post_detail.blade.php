<x-sidebar>
<div class="vh-100 d-flex">
  <div class="w-50 mt-5">
    <div class="m-3 detail_container">
      <div class="p-3">
        @foreach($post->subCategories as $sub)
            <span class="detail_tag">{{ $sub->sub_category }}</span>
        @endforeach
        <div class="detail_inner_head">
          <div>
          </div>
          {{-- 自分の投稿にのみ編集・削除ボタン表示 --}}
          @if (Auth::id() === $post->user_id)
            <div>
              <button type="button"
                      class="edit-modal-open edit-btn"
                      post_title="{{ $post->post_title }}"
                      post_body="{{ $post->post }}"
                      post_id="{{ $post->id }}">
                編集
              </button>
              <button type="button"
                      class="btn btn-danger delete-modal-open"
                      data-post-id="{{ $post->id }}">
                削除
              </button>
            </div>
          @endif
        </div>

        <div class="contributor d-flex">
          <p>
            <span>{{ $post->user->over_name }}</span>
            <span>{{ $post->user->under_name }}</span>
            さん
          </p>
          <span class="ml-5">{{ $post->created_at }}</span>
        </div>
        <div class="detsail_post_title">{{ $post->post_title }}</div>
        <div class="mt-3 detsail_post">{{ $post->post }}</div>
      </div>
      <div class="p-3">
        <div class="comment_container">
          <span class="">コメント</span>
          @foreach($post->postComments as $comment)
          <div class="comment_area border-top">
            <p>
              <span>{{ $comment->commentUser($comment->user_id)->over_name }}</span>
              <span>{{ $comment->commentUser($comment->user_id)->under_name }}</span>さん
            </p>
            <p>{{ $comment->comment }}</p>
          </div>
          @endforeach
        </div>
      </div>
    </div>
  </div>
  <div class="w-50 p-3">
    <div class="comment_container border m-5">
      <div class="comment_area p-3">
        @if ($errors->has('comment'))
            <span class="error_message">{{ $errors->first('comment') }}</span>
        @endif
        <p class="m-0">コメントする</p>
        <textarea class="w-100" name="comment" form="commentRequest"></textarea>
        <input type="hidden" name="post_id" form="commentRequest" value="{{ $post->id }}">
        <input type="submit" class="btn btn-primary" form="commentRequest" value="投稿">
        <form action="{{ route('comment.create') }}" method="post" id="commentRequest">{{ csrf_field() }}</form>
      </div>
    </div>
  </div>
</div>
<div class="modal js-modal">
  <div class="modal__bg js-modal-close"></div>
  <div class="modal__content">
    <form action="{{ route('post.edit') }}" method="post">
      <div class="w-100">
        <div class="modal-inner-title w-50 m-auto">
          <input type="text" name="post_title" placeholder="タイトル" class="w-100">
        </div>
        <div class="modal-inner-body w-50 m-auto pt-3 pb-3">
          <textarea placeholder="投稿内容" name="post_body" class="w-100"></textarea>
        </div>
        <div class="w-50 m-auto edit-modal-btn d-flex">
          <a class="js-modal-close btn btn-danger d-inline-block" href="">閉じる</a>
          <input type="hidden" class="edit-modal-hidden" name="post_id" value="">
          <input type="submit" class="btn btn-primary d-block" value="編集">
        </div>
      </div>
      {{ csrf_field() }}
    </form>
  </div>
</div>
{{-- 削除確認モーダル --}}
<div class="modal js-delete-modal">
  <div class="modal__bg js-delete-modal-close"></div>
  <div class="modal__content text-center">
    <p>この投稿を削除してもよろしいですか？</p>

    <div class="mt-3">
      <form method="GET" id="deleteForm">
        @csrf
        <button type="button" class="btn btn-secondary js-delete-modal-close">キャンセル</button>
        <button type="submit" class="btn btn-danger">削除する</button>
      </form>
    </div>
  </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {

    // 編集モーダルを開く
    $('.edit-modal-open').on('click', function() {

        let title = $(this).attr('post_title');
        let body = $(this).attr('post_body');
        let postId = $(this).attr('post_id');

        // 値が入っているかコンソールで確認可
        console.log(title, body, postId);

        // モーダル開く
        $('.js-modal').fadeIn();

        // 値をセット
        $('input[name="post_title"]').val(title);
        $('textarea[name="post_body"]').val(body);
        $('.edit-modal-hidden').val(postId);
    });

    // モーダル閉じる
    $('.js-modal-close').on('click', function() {
        $('.js-modal').fadeOut();
    });

});
</script>

<script>
  // 削除モーダルを開く
  $('.delete-modal-open').on('click', function() {
    let postId = $(this).data('post-id');
    // フォームのactionを動的にセット
    $('#deleteForm').attr('action', '/bulletin_board/delete/' + postId);
    $('.js-delete-modal').fadeIn();
  });

  // モーダルを閉じる
  $('.js-delete-modal-close').on('click', function() {
    $('.js-delete-modal').fadeOut();
  });
</script>
</x-sidebar>
