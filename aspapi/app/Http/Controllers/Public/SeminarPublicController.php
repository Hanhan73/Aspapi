<?php
namespace App\Http\Controllers\Public;
 
use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;
 
class SeminarPublicController extends Controller
{
    public function index(Request $request)
    {
        $categories = Seminar::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');
 
        $seminars = Seminar::where('is_active', true)
            ->withCount('questions')
            ->when($request->search, fn($q) => $q->where('title', 'like', '%' . $request->search . '%'))
            ->when($request->category, fn($q) => $q->where('category', $request->category))
            ->latest()
            ->paginate(12)
            ->withQueryString();
 
        return view('public.seminars.index', compact('seminars', 'categories'));
    }
}