@extends('admin')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('modal')
    <div class="modal-page-overlay">
        <div class="modal-page-window">

            {{-- × ボタン（admin に戻る） --}}
            <button class="modal-page-close" onclick="location.href='/admin'">×</button>

            <div class="modal-page-row">
                <span class="modal-page-label">お名前</span>
                <p class="modal-page-value">{{ $contact->first_name }}　{{ $contact->last_name }}</p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">性別</span>
                <p class="modal-page-value">
                    {{ $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他') }}
                </p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">メールアドレス</span>
                <p class="modal-page-value">{{ $contact->email }}</p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">電話番号</span>
                <p class="modal-page-value">{{ $contact->tel }}</p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">住所</span>
                <p class="modal-page-value">{{ $contact->address }}</p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">建物名</span>
                <p class="modal-page-value">{{ $contact->building }}</p>
            </div>

            <div class="modal-page-row">
                <span class="modal-page-label">お問い合わせの種類</span>
                <p class="modal-page-value">{{ $contact->category->content }}</p>
            </div>

            <div class="modal-page-row modal-page-content-row">
                <span class="modal-page-label">お問い合わせ内容</span>
                <p class="modal-page-value">{!! nl2br(e($contact->detail)) !!}</p>
            </div>

            {{-- 削除ボタン --}}
            <form action="{{ route('admin.delete', $contact->id) }}" method="POST" class="modal-page-delete-form">
                @csrf
                @method('DELETE')
                <button type="submit" class="modal-page-delete">削除</button>
            </form>

        </div>
    </div>
@endsection
