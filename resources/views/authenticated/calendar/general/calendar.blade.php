<x-sidebar>
  <div class="vh-100 pt-5" style="background:#ECF1F6;">
    <div class="border w-75 m-auto pt-5 pb-5" style="border-radius:5px; background:#FFF;">
      <div class="w-75 m-auto border" style="border-radius:5px;">

        <p class="text-center">{{ $calendar->getTitle() }}</p>
        <div class="">
          {!! $calendar->render() !!}
        </div>
      </div>
      <div class="text-right w-75 m-auto">
        <input type="submit" class="btn btn-primary" value="予約する" form="reserveParts">
      </div>
    </div>
  </div>

  {{-- キャンセル確認モーダル --}}
  <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0" style="border-radius:8px;">
        {{-- ヘッダー削除（タイトルなし） --}}
        <div class="modal-body text-start">
          <p><strong>予約日：</strong><span id="modalDate"></span></p>
          <p><strong>時間：</strong><span id="modalPart"></span></p>
          <p>上記の予約をキャンセルしてもよろしいですか？</p>
        </div>
        <div class="modal-footer justify-content-start border-0">
          <form method="post" action="/delete/calendar" id="deleteParts" class="d-flex w-100 justify-content-between">
            @csrf
            <input type="hidden" name="delete_date" id="modalDeleteValue">
            <div>
              <button type="button" class="btn btn-primary me-2" data-bs-dismiss="modal" style="width:120px;">閉じる</button>
            </div>
            <div>
              <button type="submit" class="btn btn-danger" style="width:120px;">キャンセル</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  {{-- JavaScriptでモーダル制御 --}}
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const cancelButtons = document.querySelectorAll('button[name="delete_date"]');
      cancelButtons.forEach(btn => {
        btn.addEventListener('click', function (e) {
          e.preventDefault();

          const date = btn.closest('td').querySelector('input[name="getData[]"]').value;
          const part = btn.textContent.trim();
          const value = btn.value;

          document.getElementById('modalDate').textContent = date;
          document.getElementById('modalPart').textContent = part;
          document.getElementById('modalDeleteValue').value = value;

          const modal = new bootstrap.Modal(document.getElementById('cancelModal'));
          modal.show();
        });
      });
    });
  </script>

  {{-- BootstrapのJSが読み込まれていない場合に備えて --}}
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</x-sidebar>
