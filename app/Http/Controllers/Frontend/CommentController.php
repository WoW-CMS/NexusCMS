<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * Store a newly created comment.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $slug)
    {
        $request->validate([
            'body' => 'required|string',
        ]);

        $news = News::where('slug', $slug)->firstOrFail();

        $comment = new Comment();
        $comment->comment = $request->body;
        $comment->user_id = Auth::id() ?? null;
        $comment->commentable_id = $news->id;
        $comment->commentable_type = News::class;
        $comment->save();

        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    /**
     * Remove the specified comment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);
        
        if (Auth::id() == $comment->user_id || (Auth::user() && Auth::user()->hasRole('Admin'))) {
            $comment->delete();
            return redirect()->back()->with('success', 'Comment deleted successfully!');
        }
        
        return redirect()->back()->with('error', 'You are not authorized to delete this comment.');
    }
}