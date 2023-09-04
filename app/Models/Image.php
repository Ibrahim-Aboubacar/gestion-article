<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'path',
        'size',
        'article_id',
    ];
    public function getPath(): string
    {
        if (str_contains($_SERVER['REQUEST_URI'], 'gestion-article/public')) {
            return "/gestion-article/public/storage/" . $this->path;
        } else {
            return Storage::disk('public')->url($this->path);
        }
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    public function getImageUrl(): string
    {
        if (str_contains($_SERVER['REQUEST_URI'], 'gestion-article/public')) {
            return "/gestion-article/public/storage/" . $this->path;
        } else {
            return Storage::disk('public')->url($this->path);
        }
    }

    public function deleteImageIfExist(): bool
    {
        $article = Article::where('image', $this->path)->first();
        if (!$article) {
            Storage::disk('public')->delete($this->path);
            return true;
        } else {
            return false;
        }
    }
}
