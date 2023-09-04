<?php

namespace App\Http\Controllers;

use App\Models\Image;
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
            }, 'images'])->where('sub_category_id', '=', $subCatId);
        } else {
            $articles = Article::with(['images', 'sub_category.category']);
        }

        $articles = $articles->orderByDesc('created_at')->paginate(10);
        $artiblesArr = [];
        foreach ($articles as $article) {
            $artImages = [];
            foreach ($article->images as $image) {
                $artImages[] = [
                    "id" => $image->id,
                    "path" => $image->getPath(),
                    "size" => $image->size,
                    "name" => $image->name,
                ];
            }
            $artiblesArr[] = [
                'id' => $article->id,
                'name' => $article->name,
                'image' => $article->getImageUrl(),
                'images' => $artImages,
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
        return view('articles.create', [
            'imageLimit' => Article::IMAGE_LIMIT,
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
            'name' => 'string|required|min:3|max:255',
            'description' => 'string|required|min:10|max:500',
            'sub_category' => 'int|required',
            'images.*' => 'required|image|mimes:jpg,png,jpeg|max:2048|',
            'images' => 'required|array|between:1,' . Article::IMAGE_LIMIT,
        ]);
        $images = $data['images'];


        $article = Article::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'sub_category_id' => $data['sub_category'],
            'user_id' => Auth::user()->id,
        ]);

        foreach ($images as $image) {
            if ($image !== null && !$image->getError()) {
                $path = $image->store('articles', 'public');
                $img = new Image();
                $img->path = $path;
                $img->name = $image->getClientOriginalName();
                $img->size = $image->getSize();
                $img->article_id = $article->id;
                $img->save();
                if (!$article->image) {
                    $article->image = $path;
                    $article->save();
                }
            }
        }
        return redirect()->route('articles.show', ['id' => $article->id])->with(['success', 'Article creé']);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $article = Article::with(['sub_category.category', 'images'])->where('id', $id)->first('*');
        if (!$article) abort(404);
        return view('articles.show', ['article' => $article]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id)
    {
        $article = Article::with(['images', "sub_category.category"])->where('id', $id)->firstOrFail();

        $categories = (Category::select(['id', 'name'])->get())->map(function ($item) {
            return $item->only(['id', 'name']);
        });
        $SubCategories = (SubCategory::with('category')->select(['id', 'name', 'category_id'])->get())->map(function ($item) {
            return $item->only(['id', 'name', 'category', 'category_id']);
        });
        return view('articles.edit', [
            'imageLimit' => Article::IMAGE_LIMIT,
            'currentImageCount' => count($article->images),
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
        $article = Article::findOrFail($id);
        $limit = (int)Article::IMAGE_LIMIT - count($article->images);
        $rules = [
            'name' => 'string|required|min:3|max:255',
            'description' => 'string|required|min:10|max:500',
            'category' => 'int|required',
            'sub_category' => 'int|required',
            'images.*' => 'image|mimes:jpg,png,jpeg|max:2048|',
            // 'images' => 'array|between:1,' . Article::IMAGE_LIMIT,
        ];
        if ($limit > 0) {
            $rules = [...$rules, 'images' => 'array|between:1,' . $limit];
        } else {
            $rules = [...$rules, 'images' => 'array|between:0,0'];
        }
        $data = $request->validate($rules);
        $category = Category::find($data['category']);
        $subCategory = SubCategory::find($data['sub_category']);
        if (!$category || !$subCategory) return back()->with(['error', 'Categorie ou Sous Categorie incorrect!']);

        // dd(count($request->file('images')), $limit, count($request->file('images')) <= $limit, $request);
        if ($request->file('images')) {
            if (count($request->file('images')) <= $limit) {
                foreach ($request->file('images') as $image) {
                    if ($image !== null && !$image->getError()) {
                        $img = new Image();
                        $img->path = $image->store('articles', 'public');
                        $img->name = $image->getClientOriginalName();
                        $img->size = $image->getSize();
                        $img->article_id = $article->id;
                        $img->save();
                        // $article->image = $image->store('articles', 'public');
                    }
                }
            } else {
                return back()->with(['error', 'Le nombre d\'image est superieur a la limite autorisé!']);
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
    public function destroy(Request $request, int $id)
    {
        // return ($request->all());
        $article = Article::findOrFail($id);
        $article->deleteImagesIfExist();
        $article->deleteImageIfExist();
        $article->delete();
        return redirect()->route('articles.index')->with('success', 'Article suprimé');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroyImage(int $id)
    {
        // return ["request" => $request->all(), "id" => $id];
        $image = Image::where("id", $id);
        if ($image) {
            if ($image->deleteImageIfExist()) {
                $image->delete();
                return [
                    "id" => $id,
                    "status" => 200,
                    "message" => "Image suprimée avec success",
                ];
            } else {
                return [
                    "id" => $id,
                    "status" => 300,
                    "message" => "Image non suprimée, impossible de supprimer une image utiliser comme photo principale d'un article, veuillez la changer plutot!",
                ];
            }
        } else {
            return [
                "id" => $id,
                "status" => 300,
                "message" => "Image non suprimée, impossible de trouver image!",
            ];
        }
        // return redirect()->route('articles.index')->with('success', 'Article suprimé');
    }

    public function updateImage(Request $request, Image $image)
    {
        $RequestImage = $request->validate(['image' => 'image|mimes:jpg,png,jpeg|max:2048'])['image'];

        if ($RequestImage !== null && !$RequestImage->getError()) {
            $article = Article::where('image', $image->path)->first();
            $image->deleteImageIfExist();
            $image->path = $RequestImage->store('articles', 'public');
            $image->name = $RequestImage->getClientOriginalName();
            $image->size = $RequestImage->getSize();
            $image->save();
            if ($article) {
                $article->deleteImageIfExist();
                $article->image = $image->path;
                $article->save();
            }
            return [
                "status" => 200,
                "id" => $image->id,
                'path' => $image->getPath(),
                'name' => $image->name,
                'size' => $image->size,
                "message" => "Image modifé avec success!",
            ];
        } else {
            return [
                "status" => 400,
                "id" => $image->id,
                'path' => $image->getPath(),
                'name' => $image->name,
                'size' => $image->size,
                "message" => "Une erreur est survenu avec l'image",
            ];
        }
    }
}
