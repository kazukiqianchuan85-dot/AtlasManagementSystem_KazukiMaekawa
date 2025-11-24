<x-sidebar>
<div class="post_create_container d-flex">
  <div class="post_create_area border w-50 m-5 p-5">
    <div class="">
      <p class="mb-0">カテゴリー</p>
      <select class="w-100" form="postCreate" name="post_category_id">
        @foreach($main_categories as $main_category)
          <optgroup label="{{ $main_category->main_category }}">
            <!-- サブカテゴリー表示 -->
            @foreach($main_category->subCategories as $sub_category)
                <option value="{{ $sub_category->id }}">{{ $sub_category->sub_category }}</option>
            @endforeach
          </optgroup>
        @endforeach
      </select>
    </div>
    <div class="mt-3">
      @if($errors->first('post_title'))
      <span class="error_message">{{ $errors->first('post_title') }}</span>
      @endif
      <p class="mb-0">タイトル</p>
      <input type="text" class="w-100" form="postCreate" name="post_title" value="{{ old('post_title') }}">
    </div>
    <div class="mt-3">
      @if($errors->first('post_body'))
      <span class="error_message">{{ $errors->first('post_body') }}</span>
      @endif
      <p class="mb-0">投稿内容</p>
      <textarea class="w-100" form="postCreate" name="post_body">{{ old('post_body') }}</textarea>
    </div>
    <div class="mt-3 text-right">
      <input type="submit" class="btn btn-primary" value="投稿" form="postCreate">
    </div>
    <form action="{{ route('post.create') }}" method="post" id="postCreate">{{ csrf_field() }}</form>
  </div>
  @can('admin')
  <div class="ml-auto mr-auto mt-5">

      <!-- ▼ ここが白い大枠 -->
      <div class="category_card">

          {{-- メインカテゴリー --}}
          <p>メインカテゴリー</p>
          @if($errors->first('main_category_name'))
              <span class="error_message">{{ $errors->first('main_category_name') }}</span>
          @endif
          <input type="text" name="main_category_name" form="mainCategoryRequest">
          <button class="btn-blue" form="mainCategoryRequest">追加</button>

          <form action="{{ route('main.category.create') }}" method="post" id="mainCategoryRequest">
              {{ csrf_field() }}
          </form>

          <hr style="margin: 20px 0;">

          {{-- サブカテゴリー --}}
          <p>サブカテゴリー</p>
          @if($errors->first('sub_category_name'))
              <span class="error_message">{{ $errors->first('sub_category_name') }}</span>
          @endif

          <select name="main_category_id" form="subCategoryRequest">
              <option value="">---</option>
              @foreach($main_categories as $main_category)
                  <option value="{{ $main_category->id }}">{{ $main_category->main_category }}</option>
              @endforeach
          </select>

          <input type="text" class="mt-2" name="sub_category_name" form="subCategoryRequest">

          <button class="btn-blue mt-2" form="subCategoryRequest">追加</button>

          <form action="{{ route('sub.category.create') }}" method="post" id="subCategoryRequest">
              {{ csrf_field() }}
          </form>

      </div>
  </div>
  @endcan

</div>
</x-sidebar>
