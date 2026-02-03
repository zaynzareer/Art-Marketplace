<?php

namespace App\Helpers;

class CategoryHelper
{
    /**
     * All available categories with their display labels
     */
    public static array $categories = [
        'all' => 'All Categories',
        'pottery' => 'Pottery',
        'paintings' => 'Paintings',
        'jewelry' => 'Jewelry',
        'sculptures' => 'Sculptures',
        'textiles' => 'Textiles',
        'glassarts' => 'Glass Arts',
        'collectibles' => 'Collectibles',
        'leathercrafts' => 'Leather Crafts',
    ];

    /**
     * Get the display label for a category
     */
    public static function getLabel(string $category): string
    {
        return self::$categories[$category] ?? ucfirst($category);
    }

    /**
     * Get all categories (excluding 'all' for product filtering)
     */
    public static function getAll(): array
    {
        return collect(self::$categories)
            ->except('all')
            ->toArray();
    }

    /**
     * Get all categories including 'all' option
     */
    public static function getAllWithAll(): array
    {
        return self::$categories;
    }
}
