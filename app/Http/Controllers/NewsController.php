<?php
namespace App\Http\Controllers;

use App\Models\News;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

class NewsController extends Controller
{
    public function index()
    {
        $news = News::with(['author', 'category'])
            ->latest()
            ->paginate(9);

        return view('news.index', compact('news'));
    }

    public function create()
    {
        if (!Gate::allows('isAdmin')) {
            Log::error('Create news access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'role_id' => auth()->user()->role_id,
                'role_name' => auth()->user()->role ? auth()->user()->role->name : null
            ]);
            abort(403, 'This action is unauthorized.');
        }

        $categories = Category::all();
        return view('news.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Gate::allows('isAdmin')) {
            Log::error('Store news access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'role_id' => auth()->user()->role_id,
                'role_name' => auth()->user()->role ? auth()->user()->role->name : null
            ]);
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'required|image|max:2048'
        ]);

        $news = News::create([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'category_id' => $validated['category_id'],
            'author_id' => auth()->id(),
            'image' => $request->file('image')->store('news', 'public')
        ]);

        return redirect()->route('news.show', $news)
            ->with('success', 'News article created successfully.');
    }

    public function edit(News $news)
    {
        if (!Gate::allows('isAdmin')) {
            Log::error('Edit news access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'role_id' => auth()->user()->role_id,
                'role_name' => auth()->user()->role ? auth()->user()->role->name : null
            ]);
            abort(403, 'This action is unauthorized.');
        }

        $categories = Category::all();
        return view('news.edit', compact('news', 'categories'));
    }

    public function update(Request $request, News $news)
    {
        if (!Gate::allows('isAdmin')) {
            Log::error('Update news access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'role_id' => auth()->user()->role_id,
                'role_name' => auth()->user()->role ? auth()->user()->role->name : null
            ]);
            abort(403, 'This action is unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|max:255',
            'content' => 'required',
            'excerpt' => 'required|max:255',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|max:2048'
        ]);

        $news->update([
            'title' => $validated['title'],
            'content' => $validated['content'],
            'excerpt' => $validated['excerpt'],
            'category_id' => $validated['category_id']
        ]);

        if ($request->hasFile('image')) {
            $news->update([
                'image' => $request->file('image')->store('news', 'public')
            ]);
        }

        return redirect()->route('news.show', $news)
            ->with('success', 'News article updated successfully.');
    }

    public function destroy(News $news)
    {
        if (!Gate::allows('isAdmin')) {
            Log::error('Delete news access denied', [
                'user_id' => auth()->id(),
                'user_email' => auth()->user()->email,
                'role_id' => auth()->user()->role_id,
                'role_name' => auth()->user()->role ? auth()->user()->role->name : null
            ]);
            abort(403, 'This action is unauthorized.');
        }

        $news->delete();

        return redirect()->route('news.index')
            ->with('success', 'News article deleted successfully.');
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }
}
