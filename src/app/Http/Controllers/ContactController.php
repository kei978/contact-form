<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\ContactFormRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Models\Category;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ContactController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('index', compact('categories'));
    }

    public function confirm(ContactFormRequest $request)
    {
        // // 修正ボタンが押された場合
        if ($request->input('action') === 'back') {
            return redirect()->route('index')->withInput();
        }
        // 性別の変換
        $genderLabels = [
            1 => '男性',
            2 => '女性',
            3 => 'その他',
        ];
        // カテゴリーの変換
        $categoryLabels = [
            1 => '商品のお届けについて',
            2 => '商品の交換について',
            3 => '商品トラブル',
            4 => 'ショップへのお問い合わせ',
            5 => 'その他',
        ];
        $contact = [
            'name' => $request->input('family-name') . '　' . $request->input('given-name'),
            'gender' => $genderLabels[$request->input('gender')] ?? '',
            'email' => $request->input('email'),
            'tel' => $request->input('tel1') . $request->input('tel2') . $request->input('tel3'),
            'address' => $request->input('address'),
            'building' => $request->input('building'),
            'category' => $categoryLabels[$request->input('category')] ?? '',
            'content' => $request->input('content'),
        ];
        return view('confirm', compact('contact'));
    }

    public function store(Request $request)
    {
        // 修正ボタンが押された場合
        if ($request->input('action') === 'back') {
            return redirect()->route('index')->withInput();
        }
        // 電話番号を結合
        $tel = $request->input('tel1') . $request->input('tel2') . $request->input('tel3');
        // DB 保存
        Contact::create([
            'category_id' => $request->category,
            'first_name' => $request->input('family-name'),
            'last_name' => $request->input('given-name'),
            'gender' => $request->gender,
            'email' => $request->email,
            'tel' => $tel,
            'address' => $request->address,
            'building' => $request->building,
            'detail' => $request->content,
        ]);
        return view('thanks');
    }

    public function admin(Request $request)
    {
        $query = Contact::query();
        // キーワード検索（名前 or メール）
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }
        // 性別
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        // カテゴリー
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        // 日付
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        // ページネーション
        $contacts = $query->paginate(7);
        // カテゴリー一覧（検索フォーム用）
        $categories = Category::all();
        return view('admin', compact('contacts', 'categories'));
    }

    public function detail(Request $request, $id)
    {
        $contact = Contact::findOrFail($id);
        // admin() と同じ検索ロジックをそのまま使う
        $query = Contact::query();
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        $contacts = $query->paginate(7);
        $categories = Category::all();
        return view('detail', compact('contact', 'contacts', 'categories'));
    }

    public function destroy($id)
    {
        Contact::findOrFail($id)->delete();
        return redirect('/admin');
    }

    public function showRegisterForm()
    {
        return view('register');
    }

    public function registerStore(RegisterUserRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);
        return redirect('admin');
    }

    public function export(Request $request)
    {
        $query = Contact::query();
        // キーワード検索
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('first_name', 'like', "%$keyword%")
                    ->orWhere('last_name', 'like', "%$keyword%")
                    ->orWhere('email', 'like', "%$keyword%");
            });
        }
        // 性別
        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }
        // カテゴリー
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        // 日付
        if ($request->filled('date')) {
            $query->whereDate('created_at', $request->date);
        }
        // 絞り込んだデータを取得
        $contacts = $query->get();
        // CSV 作成
        $csv = "名前,性別,メールアドレス,お問い合わせ種類\n";
        foreach ($contacts as $contact) {
            $gender = $contact->gender == 1 ? '男性' : ($contact->gender == 2 ? '女性' : 'その他');
            $csv .= "{$contact->first_name} {$contact->last_name},{$gender},{$contact->email},{$contact->category->content}\n";
        }
        $csv_sjis = mb_convert_encoding($csv, 'SJIS-win', 'UTF-8');
        return response($csv_sjis)
            ->header('Content-Type', 'text/csv; charset=Shift_JIS')
            ->header('Content-Disposition', 'attachment; filename=contacts.csv');
    }
}
