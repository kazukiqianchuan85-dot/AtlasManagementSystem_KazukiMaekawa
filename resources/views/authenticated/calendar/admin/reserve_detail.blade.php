<x-sidebar>
    <div class="pt-5" style="background:#ECF1F6; min-height:100vh;">

        <div class="w-50 m-auto p-4 bg-white border" style="border-radius: 5px;">

            {{-- 日付 + 部数 --}}
            <p class="mb-3">
                <span class="fw-bold">{{ $date }}　{{ $part }}部</span>
            </p>

            {{-- テーブル --}}
            <table class="table table-bordered bg-white">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>名前</th>
                        <th>場所</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($reservePersons as $reserve)
                        @foreach($reserve->users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->over_name }} {{ $user->under_name }}</td>
                                <td>リモート</td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
</x-sidebar>
