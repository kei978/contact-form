@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
@endsection

@section('header__right')
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="header__login-btn">logout</button>
    </form>
@endsection

@section('content')
    <div class="admin-container">
        <div class="admin-title">
            <h2>Admin</h2>
        </div>
        <form action="/admin" method="GET" class="search-row">
            <input type="text" name="keyword" placeholder="名前やメールアドレスを入力してください" value="{{ request('keyword') }}">
            <div class="select-wrapper">
                <select name="gender" class="select-gender">
                    <option value="">性別</option>
                    <option value="1" {{ request('gender') == 1 ? 'selected' : '' }}>男性</option>
                    <option value="2" {{ request('gender') == 2 ? 'selected' : '' }}>女性</option>
                    <option value="3" {{ request('gender') == 3 ? 'selected' : '' }}>その他</option>
                </select>
            </div>
            <div class="select-wrapper">
                <select name="category" class="select-category">
                    <option value="">お問い合わせの種類</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                            {{ $category->content }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="date-wrapper">
                <span class="date-display">年/月/日</span>
                <span class="date-arrow">▼</span>
                <input type="date" name="date" class="date-input" value="{{ request('date') }}">
            </div>
            <button type="submit" class="search-btn">検索</button>
            <a href="/admin" class="reset-btn">リセット</a>
        </form>
        <div class="export-pagination-row">
            <a href="{{ route('admin.export', request()->query()) }}" class="export-btn">エクスポート</a>
            <div class="pagination-area">
                {{ $contacts->appends(request()->query())->links('pagination::bootstrap-4') }}
            </div>
        </div>
        <table class="admin-table">
            <thead>
                <tr>
                    <th>お名前</th>
                    <th>性別</th>
                    <th>メールアドレス</th>
                    <th>お問い合わせの種類</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->first_name }}　{{ $contact->last_name }}</td>
                        <td>{{ $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他') }}</td>
                        <td>{{ $contact->email }}</td>
                        <td>{{ $contact->category->content }}</td>
                        <td><a href="/admin/detail/{{ $contact->id }}" class="detail-btn">詳細</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const input = document.querySelector(".date-input");
            const display = document.querySelector(".date-display");

            function formatDate(value) {
                if (!value) return "";
                const [y, m, d] = value.split("-");
                return `${y}/${m}/${d}`;
            }
            if (input.value) {
                display.textContent = formatDate(input.value);
            }
            input.addEventListener("change", () => {
                display.textContent = formatDate(input.value);
            });
        });
    </script>
@endsection

@yield('modal')

