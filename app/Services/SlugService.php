<?php

namespace App\Services;

use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;

class SlugService
{
    /**
     * Generate a clean, URL-friendly slug for products
     */
    public static function generateProductSlug($productName, $brandName = null, $barcode = null)
    {
        // Clean product name
        $cleanName = self::cleanProductName($productName);
        
        // Generate base slug
        if ($barcode && !empty($barcode)) {
            // Use barcode for uniqueness if available
            $baseSlug = Str::slug($cleanName . '-' . $barcode);
        } else {
            // Use brand name for uniqueness if no barcode
            $baseSlug = Str::slug($cleanName);
            if ($brandName && !empty($brandName)) {
                $brandSlug = Str::slug($brandName);
                // Only add brand if it's not already in the product name
                if (!str_contains($baseSlug, $brandSlug)) {
                    $baseSlug .= '-' . $brandSlug;
                }
            }
        }
        
        // Ensure uniqueness
        $slug = self::ensureUniqueSlug($baseSlug, 'products');
        
        return $slug;
    }
    
    /**
     * Generate clean slug for brands
     */
    public static function generateBrandSlug($brandName)
    {
        $baseSlug = Str::slug($brandName);
        return self::ensureUniqueSlug($baseSlug, 'brands');
    }
    
    /**
     * Generate clean slug for categories
     */
    public static function generateCategorySlug($categoryName)
    {
        $baseSlug = Str::slug($categoryName);
        return self::ensureUniqueSlug($baseSlug, 'categories');
    }
    
    /**
     * Clean product name for better slugs
     */
    private static function cleanProductName($name)
    {
        // Remove common patterns that don't add value to URLs
        $patterns = [
            '/^\#\d+\s*/', // Remove leading numbers like "#123 "
            '/\s*\([^)]*\)/', // Remove content in parentheses
            '/\s*\[[^\]]*\]/', // Remove content in brackets
            '/\s*".*"/', // Remove quoted content
            '/\s*-\s*[^-]*$/', // Remove trailing parts after dash
            '/\s*with\s+.*/i', // Remove "with..." descriptions
            '/\s*\d+g$/i', // Remove gram measurements
            '/\s*\d+oz$/i', // Remove ounce measurements
        ];
        
        $cleaned = preg_replace($patterns, '', $name);
        
        // Remove extra whitespace and trim
        $cleaned = trim(preg_replace('/\s+/', ' ', $cleaned));
        
        return $cleaned;
    }
    
    /**
     * Ensure slug is unique in the specified table
     */
    private static function ensureUniqueSlug($baseSlug, $table)
    {
        $slug = $baseSlug;
        $counter = 1;
        
        // Check uniqueness based on table type
        $exists = false;
        switch ($table) {
            case 'products':
                $exists = Product::where('slug', $slug)->exists();
                break;
            case 'brands':
                $exists = Brand::where('slug', $slug)->exists();
                break;
            case 'categories':
                $exists = Category::where('slug', $slug)->exists();
                break;
        }
        
        while ($exists) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
            
            // Check again
            switch ($table) {
                case 'products':
                    $exists = Product::where('slug', $slug)->exists();
                    break;
                case 'brands':
                    $exists = Brand::where('slug', $slug)->exists();
                    break;
                case 'categories':
                    $exists = Category::where('slug', $slug)->exists();
                    break;
            }
        }
        
        return $slug;
    }
    
    /**
     * Clean existing product slugs
     */
    public static function cleanExistingProductSlugs($limit = 100)
    {
        $products = Product::where('slug', 'like', '%-%-%-%')
            ->orWhere('slug', 'like', '%-%-%-%-%')
            ->limit($limit)
            ->get();
            
        $updated = 0;
        
        foreach ($products as $product) {
            $brandName = $product->brand ? $product->brand->name : null;
            $newSlug = self::generateProductSlug($product->name, $brandName, $product->barcode);
            
            if ($newSlug !== $product->slug) {
                $product->slug = $newSlug;
                $product->save();
                $updated++;
                
                echo "Updated slug for product {$product->id}: {$product->slug}\n";
            }
        }
        
        return $updated;
    }
    
    /**
     * Validate slug format
     */
    public static function isValidSlug($slug)
    {
        return preg_match('/^[a-z0-9\-_]+$/', $slug) && !str_contains($slug, '--');
    }
}
