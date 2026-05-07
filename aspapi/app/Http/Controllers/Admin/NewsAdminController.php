<?php
// app/Http/Controllers/Admin/NewsAdminController.php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewsAdminController extends Controller
{
    public function index()
    {
        $news = News::latest()->paginate(15);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'excerpt'   => 'nullable|string|max:500',
            'category'  => 'nullable|string|max:100',
            'status'    => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => 'nullable|date'
        ]);

        $data = [
            'title'        => $request->title,
            'slug'         => $this->uniqueSlug($request->title),
            'body'         => $request->body,
            'excerpt'      => $request->excerpt,
            'category'     => $request->category,
            'status'       => $request->status,
            'user_id'      => auth()->id(),
            'published_at' => $request->published_at
                ? \Carbon\Carbon::parse($request->published_at)
                : ($request->status === 'published' ? now() : null),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        News::create($data);

        return redirect()->route('admin.news.index')
                         ->with('success', 'Berita berhasil ditambahkan.');
    }

    public function edit(News $news)
    {
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $request->validate([
            'title'     => 'required|string|max:255',
            'body'      => 'required|string',
            'excerpt'   => 'nullable|string|max:500',
            'category'  => 'nullable|string|max:100',
            'status'    => 'required|in:draft,published',
            'thumbnail' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => 'nullable|date'
        ]);

        $data = [
            'title'        => $request->title,
            'body'         => $request->body,
            'excerpt'      => $request->excerpt,
            'category'     => $request->category,
            'status'       => $request->status,
            'published_at' => $request->published_at
                            ? \Carbon\Carbon::parse($request->published_at)
                            : ($request->status === 'published' && !$news->published_at ? now() : $news->published_at),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($news->thumbnail) Storage::disk('public')->delete($news->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('news', 'public');
        }

        $news->update($data);

        return redirect()->route('admin.news.index')
                         ->with('success', 'Berita berhasil diperbarui.');
    }

    public function destroy(News $news)
    {
        if ($news->thumbnail) Storage::disk('public')->delete($news->thumbnail);
        $news->delete();
        return back()->with('success', 'Berita berhasil dihapus.');
    }

    private function uniqueSlug(string $title): string
    {
        $slug = Str::slug($title);
        $count = News::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
}