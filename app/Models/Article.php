<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;

    /**
     * The attribute that limits the number of associated images.
     *
     * @var array<int, string>
     */
    public const IMAGE_LIMIT = 5;

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

    public function images()
    {
        return $this->hasMany(Image::class);
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
    public function deleteImagesIfExist()
    {
        $images = $this->images;
        if ($images) {
            foreach ($images as $image) {
                Storage::disk('public')->delete($image->path);
                $image->delete();
            }
        }
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
            $this->image = '';
        }
    }
}
