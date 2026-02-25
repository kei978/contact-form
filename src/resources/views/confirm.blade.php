@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/confirm.css') }}">
@endsection

@section('content')
    @php
        $request = request();
    @endphp
    <div class="confirm__content">
        <div class="confirm__heading">
            <h2>Confirm</h2>
        </div>
        <form action="/confirm" class="form" method="POST">
            @csrf
            <div class="confirm-table">
                <table class="confirm-table__inner">
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お名前</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['name'] }}" readonly>
                            <input type="hidden" name="family-name" value="{{ $request->input('family-name') }}">
                            <input type="hidden" name="given-name" value="{{ $request->input('given-name') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">性別</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['gender'] }}" readonly>
                            <input type="hidden" name="gender" value="{{ request('gender') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">メールアドレス</th>
                        <td class="confirm-table__text">
                            <input type="email" value="{{ $contact['email'] }}" readonly>
                            <input type="hidden" name="email" value="{{ $request->input('email') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">電話番号</th>
                        <td class="confirm-table__text">
                            <input type="tel" value="{{ $contact['tel'] }}" readonly>
                            <input type="hidden" name="tel1" value="{{ $request->input('tel1') }}">
                            <input type="hidden" name="tel2" value="{{ $request->input('tel2') }}">
                            <input type="hidden" name="tel3" value="{{ $request->input('tel3') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">住所</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['address'] }}" readonly>
                            <input type="hidden" name="address" value="{{ $request->input('address') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">建物名</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['building'] }}" readonly>
                            <input type="hidden" name="building" value="{{ $request->input('building') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お問い合わせの種類</th>
                        <td class="confirm-table__text">
                            <input type="text" value="{{ $contact['category'] }}" readonly>
                            <input type="hidden" name="category" value="{{ request('category') }}">
                        </td>
                    </tr>
                    <tr class="confirm-table__row">
                        <th class="confirm-table__header">お問い合わせ内容</th>
                        <td class="confirm-table__text">
                            <div class="confirm-content">
                                {!! nl2br(e($contact['content'])) !!}
                            </div>
                            <input type="hidden" name="content" value="{{ $request->input('content') }}">
                        </td>
                    </tr>
                </table>
            </div>
            <div class="form__button">
                <button class="form__button-submit" formaction="/thanks" type="submit">送信</button>
                <button class="form__button-correction" type="submit" name="action" value="back">修正</button>
            </div>
        </form>
    </div>
@endsection
