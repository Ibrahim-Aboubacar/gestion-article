<?php

namespace App\Http\Controllers;

use id;
use App\Models\Article;
use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Database\Eloquent\Builder;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->get('category') && $request->get('sub_category')) {
            $catId = (int)$request->get('category');
            $subCatId = (int)$request->get('sub_category');
            $articles = Article::with(['sub_category' => function (Builder $query) {
                /**
                 * @var Illuminate\Contracts\Database\Eloquent\Builder $query
                 */
                $query->with('category');
            }])->where('sub_category_id', '=', $subCatId);
        } else {
            $articles = Article::with('sub_category.category');
        }

        $articles = $articles->orderByDesc('created_at')->paginate(10);
        $artiblesArr = [];
        foreach ($articles as $article) {
            $artiblesArr[] = [
                'id' => $article->id,
                'name' => $article->name,
                'image' => $article->getImageUrl(),
                'show_link' => route('articles.show', ['id' => $article->id]),
                'edit_link' => route('articles.edit', ['id' => $article->id]),
                'destroy_link' => route('articles.destroy', ['id' => $article->id]),
                'category' => $article->sub_category->category->name,
                'subCategory' => $article->sub_category->name,
            ];
        }
        $categories = (Category::select(['id', 'name'])->get())->map(function ($item) {
            return $item->only(['id', 'name']);
        });
        $SubCategories = (SubCategory::with('category')->select(['id', 'name', 'category_id'])->get())->map(function ($item) {
            return $item->only(['id', 'name', 'category', 'category_id']);
        });

        return view('articles.index', [
            'articles' => json_encode($artiblesArr),
            'pagination' => $articles,
            'count' => count($artiblesArr),
            'categories' => json_encode($categories),
            'subCategories' => json_encode($SubCategories),
            'curentCategory' => $catId ?? $categories[0]['id'],
            'curentSubCategory' => $subCatId ?? $SubCategories[0]['id'],
            'filtre' => isset($subCatId) ? 'true' : 'false',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = (Category::select(['id', 'name'])->get())->map(function ($item) {
            return $item->only(['id', 'name']);
        });
        $SubCategories = (SubCategory::with('category')->select(['id', 'name', 'category_id'])->get())->map(function ($item) {
            return $item->only(['id', 'name', 'category', 'category_id']);
        });
        // dd(json_encode($SubCategories));
        return view('articles.create', [
            'categories' => json_encode($categories),
            'sub_categories' => json_encode($SubCategories),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'string|required|min:3',
            'description' => 'string|required|min:10',
            'sub_category' => 'string|required',
            'image' => 'file|required',
        ]);

        $image = $data['image'];
        if ($image !== null && !$image->getError()) {
            $data['image'] = $image->store('articles', 'public');
        } else {
            return back()->with('error', 'Une erreur s\'est produite avec l\'upload de l\'image.');
        }

        $article = Article::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'sub_category_id' => $data['sub_category'],
            'user_id' => Auth::user()->id,
            'image' => $data['image'],
        ]);
        return redirect()->route('articles.show', ['id' => $article->id])->with(['success', 'Article creé']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = Article::findOrFail($id);

        return view('articles.show', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $article = Article::with("sub_category.category")->where('id', $id)->firstOrFail();

        $categories = (Category::select(['id', 'name'])->get())->map(function ($item) {
            return $item->only(['id', 'name']);
        });
        $SubCategories = (SubCategory::with('category')->select(['id', 'name', 'category_id'])->get())->map(function ($item) {
            return $item->only(['id', 'name', 'category', 'category_id']);
        });
        return view('articles.edit', [
            'article' => $article,
            'categories' => json_encode($categories),
            'sub_categories' => json_encode($SubCategories),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        $data = $request->validate([
            'name' => 'string|required|min:3',
            'description' => 'string|required|min:10',
            'category' => 'int|required',
            'sub_category' => 'string|required',
        ]);
        $article = Article::findOrFail($id);
        $category = Category::find($data['category']);
        $subCategory = SubCategory::find($data['sub_category']);
        if (!$category || !$subCategory) return back()->with(['error', 'Categorie ou Sous Categorie incorrect!']);

        if ($request->file('image')) {
            /**@var $image UploadedFile  */
            $image = ($request->validate(['image' => 'file']))['image'];
            if ($image !== null && !$image->getError()) {
                // suprrimer l'ancienne image si elle existe
                $article->deleteImageIfExist();
                $article->image = $image->store('articles', 'public');
            }
        }

        $article->name = $data['name'];
        $article->description = $data['description'];
        $article->sub_Category_id = $subCategory->id;
        $article->save();
        return redirect()->route('articles.show', ['id' => $id])->with('success', 'Article modifié');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id)
    {
        $article = Article::findOrFail($id);
        $article->deleteImageIfExist();
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article suprimé');
    }
}
