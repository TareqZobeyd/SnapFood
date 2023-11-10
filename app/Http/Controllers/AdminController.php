<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function showUsers()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }
    public function showComments()
    {
        $comments = Comment::all();

        return view('admin.comments.index', compact('comments'));
    }
}
