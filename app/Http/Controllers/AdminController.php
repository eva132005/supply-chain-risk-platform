<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Port;
use App\Models\Article;
use App\Models\Country;
use App\Models\RiskScore;
use App\Models\NewsCache;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Dashboard Admin
    public function index()
    {
        $totalUsers    = User::count();
        $totalPorts    = Port::count();
        $totalArticles = Article::count();
        $totalRisks    = RiskScore::count();
        $recentUsers   = User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'totalPorts', 'totalArticles', 'totalRisks', 'recentUsers'
        ));
    }

    // User Management
    public function users()
    {
        $users = User::latest()->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function updateUserRole(Request $request, User $user)
    {
        $user->update(['role' => $request->role]);
        return back()->with('success', 'Role user berhasil diupdate!');
    }

    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak bisa menghapus akun sendiri!');
        }
        $user->delete();
        return back()->with('success', 'User berhasil dihapus!');
    }

    // Article Management
    public function articles()
    {
        $articles  = Article::with(['user', 'country'])->latest()->paginate(10);
        $countries = Country::orderBy('name')->get();
        return view('admin.articles', compact('articles', 'countries'));
    }

    public function storeArticle(Request $request)
    {
        $request->validate([
            'title'      => 'required|string|max:255',
            'content'    => 'required|string',
            'category'   => 'nullable|string',
            'country_id' => 'nullable|exists:countries,id',
        ]);

        Article::create([
            'user_id'      => auth()->id(),
            'country_id'   => $request->country_id,
            'title'        => $request->title,
            'content'      => $request->content,
            'category'     => $request->category,
            'is_published' => true,
        ]);

        return back()->with('success', 'Artikel berhasil ditambahkan!');
    }

    public function deleteArticle(Article $article)
    {
        $article->delete();
        return back()->with('success', 'Artikel berhasil dihapus!');
    }

    // Port Management
    public function ports()
    {
        $ports = Port::with('country')->latest()->paginate(20);
        return view('admin.ports', compact('ports'));
    }

    public function deletePort(Port $port)
    {
        $port->delete();
        return back()->with('success', 'Port berhasil dihapus!');
    }
}