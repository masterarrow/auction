<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lot extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'lot_categories', 'lot_id', 'category_id');
    }

    /**
     * @param int $id
     * @param array $category_ids
     */
    public static function associateCategories(int $id, array $category_ids) {
        foreach ($category_ids as $cat_id) {
            if (Category::where('id', $cat_id)->first()) {
                LotCategories::create([
                    'lot_id' => $id,
                    'category_id' => $cat_id
                ]);
            }
        }
    }
}
