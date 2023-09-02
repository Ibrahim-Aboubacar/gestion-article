<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'image',
        'sub_category_id',
        'user_id',
    ];
    public function sub_category()
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function getImageUrl(): string
    {
        if (str_contains($_SERVER['REQUEST_URI'], 'gestion-article/public')) {
            return "/gestion-article/public/storage/" . $this->image . "";
        } else {
            return Storage::disk('public')->url($this->image);
        }
    }

    public function deleteImageIfExist()
    {

        if ($this->image) {
            Storage::disk('public')->delete($this->image);
            $this->image = '';
        }
    }
}