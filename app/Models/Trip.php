<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Spatie\Searchable\Searchable;
use Spatie\Searchable\SearchResult;

class Trip extends Model implements Searchable
{
    use HasFactory;
    protected $guarded = [];
    protected $table='new_trips';
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'cover',
        'location',
        'duration',
        'departure',
        'price',
        'languages',
        'group-size',
        'categories',
        'transportation',
        'includes',
        'excludes',
        'city'
    ];

    public function getSearchResult(): SearchResult
    {
        return new SearchResult(
            $this,
            $this->name,
        );
    }
}
