<?php

namespace App\Helpers;

use App\Services\SchemaService;
use App\Models\Product;
use App\Models\Category;
use App\Models\Certifier;

class SchemaHelper
{
    /**
     * Generate complete schema for product page
     */
    public static function productPageSchema(Product $product)
    {
        $schemas = [];
        
        // Product schema
        $schemas[] = SchemaService::productSchema($product);
        
        // Breadcrumb schema
        $breadcrumbs = [
            ['name' => 'Home', 'url' => config('app.url')],
            ['name' => 'Products', 'url' => route('products.index')],
            ['name' => $product->name, 'url' => route('products.show', $product->slug)]
        ];
        $schemas[] = SchemaService::breadcrumbSchema($breadcrumbs);
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Generate schema for category page
     */
    public static function categoryPageSchema(Category $category, $products)
    {
        $schemas = [];
        
        // Collection page schema
        $schemas[] = SchemaService::collectionPageSchema($category, $products);
        
        // Breadcrumb schema
        $breadcrumbs = [
            ['name' => 'Home', 'url' => config('app.url')],
            ['name' => 'Categories', 'url' => route('categories.index')],
            ['name' => $category->name, 'url' => route('categories.show', $category->slug)]
        ];
        $schemas[] = SchemaService::breadcrumbSchema($breadcrumbs);
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Generate schema for certifier page
     */
    public static function certifierPageSchema(Certifier $certifier)
    {
        $schemas = [];
        
        // Certifier schema
        $schemas[] = SchemaService::certifierSchema($certifier);
        
        // Breadcrumb schema
        $breadcrumbs = [
            ['name' => 'Home', 'url' => config('app.url')],
            ['name' => 'Certifiers', 'url' => route('certifiers.index')],
            ['name' => $certifier->name, 'url' => route('certifiers.show', $certifier->slug)]
        ];
        $schemas[] = SchemaService::breadcrumbSchema($breadcrumbs);
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Generate schema for search results page
     */
    public static function searchPageSchema($query, $products)
    {
        $schemas = [];
        
        // Website schema with search action
        $schemas[] = SchemaService::webSiteSchema();
        
        // Breadcrumb schema
        $breadcrumbs = [
            ['name' => 'Home', 'url' => config('app.url')],
            ['name' => 'Search Results', 'url' => route('search') . '?q=' . urlencode($query)]
        ];
        $schemas[] = SchemaService::breadcrumbSchema($breadcrumbs);
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Generate schema for home page
     */
    public static function homePageSchema()
    {
        $schemas = [];
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        // Website schema
        $schemas[] = SchemaService::webSiteSchema();
        
        // How-to schema
        $schemas[] = SchemaService::howToSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Generate schema for FAQ/help page
     */
    public static function faqPageSchema()
    {
        $schemas = [];
        
        // FAQ schema
        $faqs = [
            [
                'question' => 'What is kosher certification?',
                'answer' => 'Kosher certification is a process by which food products are verified to comply with Jewish dietary laws.'
            ],
            [
                'question' => 'How do I check if a product is kosher?',
                'answer' => 'Look for kosher symbols on packaging or search our database by product name or barcode.'
            ],
            [
                'question' => 'What do the different kosher symbols mean?',
                'answer' => 'Different symbols represent different certification agencies like OU, KMD, Ajdut, etc.'
            ],
            [
                'question' => 'Is kosher certification the same as halal?',
                'answer' => 'No, kosher follows Jewish dietary laws while halal follows Islamic dietary laws.'
            ],
            [
                'question' => 'How often are kosher certifications renewed?',
                'answer' => 'Certifications are typically renewed annually with regular inspections.'
            ]
        ];
        $schemas[] = SchemaService::faqSchema($faqs);
        
        // Organization schema
        $schemas[] = SchemaService::organizationSchema();
        
        return self::renderMultipleSchemas($schemas);
    }
    
    /**
     * Render multiple schemas as separate script tags
     */
    private static function renderMultipleSchemas($schemas)
    {
        $output = '';
        foreach ($schemas as $schema) {
            $output .= SchemaService::render($schema) . "\n";
        }
        return $output;
    }
    
    /**
     * Generate microdata for product cards
     */
    public static function productCardMicrodata(Product $product)
    {
        $data = [
            'itemscope' => '',
            'itemtype' => 'https://schema.org/Product'
        ];
        
        $attributes = [];
        $attributes['itemprop'] = 'name';
        $attributes['content'] = $product->name;
        
        return [
            'container' => $data,
            'name' => $attributes
        ];
    }
    
    /**
     * Generate structured data for breadcrumbs
     */
    public static function breadcrumbMicrodata($breadcrumbs)
    {
        $output = '<nav aria-label="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">';
        
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $output .= sprintf(
                '<span itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                    <meta itemprop="position" content="%d">
                    <a itemprop="item" href="%s" title="%s">
                        <span itemprop="name">%s</span>
                    </a>
                </span>',
                $index + 1,
                $breadcrumb['url'],
                $breadcrumb['name'],
                $breadcrumb['name']
            );
            
            if ($index < count($breadcrumbs) - 1) {
                $output .= ' &raquo; ';
            }
        }
        
        $output .= '</nav>';
        
        return $output;
    }
}
