<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogAdminController extends Controller
{
public function index(Request $request)
{
    $search   = trim($request->get('search', ''));
    $status   = $request->get('status', '');

    $keywords = $search
        ? collect(explode(',', $search))
            ->map(fn($k) => trim($k))
            ->filter(fn($k) => $k !== '')
            ->values()
            ->all()
        : [];

    $query = Blog::latest();

    if ($status) {
        $query->where('status', $status);
    }

    foreach ($keywords as $keyword) {
        $query->where(function ($q) use ($keyword) {
            $q->where('title',       'like', "%{$keyword}%")
              ->orWhere('excerpt',   'like', "%{$keyword}%")
              ->orWhere('author_name','like', "%{$keyword}%")
              ->orWhere('category',  'like', "%{$keyword}%");
        });
    }

    $blogs = $query->paginate(15)->withQueryString();

    return view('admin.blogs.index', compact('blogs'));
}

    public function create()
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'excerpt'      => 'nullable|string|max:500',
            'category'     => 'nullable|string|max:100',
            'author_name'  => 'nullable|string|max:255',
            'status'       => 'required|in:draft,published',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title'        => $request->title,
            'slug'         => $this->uniqueSlug($request->title),
            'body'         => $request->body,
            'excerpt'      => $request->excerpt,
            'category'     => $request->category,
            'author_name'  => $request->author_name,
            'status'       => $request->status,
            'user_id'      => auth()->id(),
            'published_at' => $request->published_at
                ? \Carbon\Carbon::parse($request->published_at)
                : ($request->status === 'published' ? now() : null),
        ];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        Blog::create($data);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog berhasil ditambahkan.');
    }

    public function edit(Blog $blog)
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog)
    {
        $request->validate([
            'title'        => 'required|string|max:255',
            'body'         => 'required|string',
            'excerpt'      => 'nullable|string|max:500',
            'category'     => 'nullable|string|max:100',
            'author_name'  => 'nullable|string|max:255',
            'status'       => 'required|in:draft,published',
            'thumbnail'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'published_at' => 'nullable|date',
        ]);

        $data = [
            'title'        => $request->title,
            'body'         => $request->body,
            'excerpt'      => $request->excerpt,
            'category'     => $request->category,
            'author_name'  => $request->author_name,
            'status'       => $request->status,
            // FIX: pakai $blog bukan $news
            'published_at' => $request->published_at
                ? \Carbon\Carbon::parse($request->published_at)
                : ($request->status === 'published' && !$blog->published_at ? now() : $blog->published_at),
        ];

        if ($request->hasFile('thumbnail')) {
            if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        $blog->update($data);

        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog berhasil diperbarui.');
    }

    public function destroy(Blog $blog)
    {
        if ($blog->thumbnail) Storage::disk('public')->delete($blog->thumbnail);
        $blog->delete();
        return redirect()->route('admin.blogs.index')
                         ->with('success', 'Blog berhasil dihapus.');
    }

    private function uniqueSlug(string $title): string
    {
        $slug  = Str::slug($title);
        $count = Blog::where('slug', 'like', $slug . '%')->count();
        return $count ? $slug . '-' . ($count + 1) : $slug;
    }
}