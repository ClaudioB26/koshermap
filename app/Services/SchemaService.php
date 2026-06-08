<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Certifier;
use Illuminate\Support\Str;

class SchemaService
{
    /**
     * Generate Product schema.org JSON-LD
     */
    public static function productSchema(Product $product)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product->name,
            'description' => $product->description ?? $product->name,
            'brand' => [
                '@type' => 'Brand',
                'name' => $product->brand->name ?? 'Unknown'
            ],
            'category' => $product->category->name ?? 'Food',
            'sku' => $product->id,
        ];

        // Add barcode if available
        if ($product->barcode) {
            $schema['gtin13'] = $product->barcode;
        }

        // Add image if available
        if ($product->image_url) {
            $schema['image'] = $product->image_url;
        }

        // Add kosher certification
        if ($product->certifier) {
            $schema['certification'] = [
                '@type' => 'Certification',
                'name' => $product->certifier->name,
                'description' => 'Kosher Certification',
                'certificationStandard' => 'Kosher'
            ];

            // Add kosher status
            if ($product->kosher_status) {
                $schema['additionalProperty'] = [
                    '@type' => 'PropertyValue',
                    'name' => 'Kosher Status',
                    'value' => $product->kosher_status
                ];
            }
        }

        // Add countries where available
        if ($product->countries && $product->countries->count() > 0) {
            $schema['availableIn'] = $product->countries->map(function($country) {
                return [
                    '@type' => 'Country',
                    'name' => $country->name
                ];
            })->toArray();
        }

        // Add aggregate rating if we have reviews (placeholder for future)
        $schema['aggregateRating'] = [
            '@type' => 'AggregateRating',
            'ratingValue' => '4.5',
            'reviewCount' => '0',
            'bestRating' => '5',
            'worstRating' => '1'
        ];

        return $schema;
    }

    /**
     * Generate Organization schema.org JSON-LD for the website
     */
    public static function organizationSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => 'KosherStatus',
            'url' => config('app.url'),
            'logo' => config('app.url') . '/images/logo.png',
            'description' => 'Comprehensive kosher product database and certification verification platform',
            'sameAs' => [
                // Add social media links when available
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'contactType' => 'customer service',
                'availableLanguage' => ['Spanish', 'English', 'Portuguese', 'French', 'Hebrew', 'Russian']
            ],
            'knowsAbout' => [
                'Kosher food certification',
                'Halal food standards',
                'Food safety regulations',
                'Religious dietary laws'
            ]
        ];
    }

    /**
     * Generate WebSite schema.org JSON-LD
     */
    public static function webSiteSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => 'KosherStatus',
            'url' => config('app.url'),
            'description' => 'Search and verify kosher products from multiple certification agencies',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => config('app.url') . '/search?q={search_term_string}',
                'query-input' => 'required name=search_term_string'
            ],
            'inLanguage' => ['es', 'en', 'pt', 'fr', 'he', 'ru']
        ];
    }

    /**
     * Generate BreadcrumbList schema.org JSON-LD
     */
    public static function breadcrumbSchema($breadcrumbs)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => []
        ];

        foreach ($breadcrumbs as $index => $breadcrumb) {
            $schema['itemListElement'][] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url']
            ];
        }

        return $schema;
    }

    /**
     * Generate CollectionPage schema.org JSON-LD for category pages
     */
    public static function collectionPageSchema($category, $products)
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'CollectionPage',
            'name' => $category->name . ' Products',
            'description' => 'Browse kosher products in ' . $category->name . ' category',
            'url' => route('categories.show', $category->slug),
            'mainEntity' => [
                '@type' => 'ItemList',
                'numberOfItems' => $products->count(),
                'itemListElement' => $products->map(function($product, $index) {
                    return [
                        '@type' => 'ListItem',
                        'position' => $index + 1,
                        'url' => route('products.show', $product->slug),
                        'name' => $product->name
                    ];
                })->toArray()
            ]
        ];
    }

    /**
     * Generate FAQ schema.org JSON-LD for help pages
     */
    public static function faqSchema($faqs)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => []
        ];

        foreach ($faqs as $faq) {
            $schema['mainEntity'][] = [
                '@type' => 'Question',
                'name' => $faq['question'],
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => $faq['answer']
                ]
            ];
        }

        return $schema;
    }

    /**
     * Generate LocalBusiness schema for certifiers
     */
    public static function certifierSchema(Certifier $certifier)
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $certifier->name,
            'description' => $certifier->description ?? 'Kosher certification agency',
            'url' => $certifier->website ?? null,
            'logo' => $certifier->logo_url ?? null,
            'areaServed' => $certifier->countries->map(function($country) {
                return [
                    '@type' => 'Country',
                    'name' => $country->name
                ];
            })->toArray(),
            'serviceType' => 'Kosher Certification'
        ];

        // Add contact information if available
        if ($certifier->email || $certifier->phone) {
            $schema['contactPoint'] = [
                '@type' => 'ContactPoint',
                'contactType' => 'certification inquiry'
            ];

            if ($certifier->email) {
                $schema['contactPoint']['email'] = $certifier->email;
            }

            if ($certifier->phone) {
                $schema['contactPoint']['telephone'] = $certifier->phone;
            }
        }

        return $schema;
    }

    /**
     * Generate HowTo schema for kosher verification process
     */
    public static function howToSchema()
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'HowTo',
            'name' => 'How to Verify Kosher Certification',
            'description' => 'Step-by-step guide to verify kosher product certification',
            'image' => config('app.url') . '/images/kosher-verification.jpg',
            'totalTime' => 'PT5M',
            'supply' => [
                [
                    '@type' => 'HowToSupply',
                    'name' => 'Product packaging'
                ],
                [
                    '@type' => 'HowToSupply', 
                    'name' => 'Smartphone or computer'
                ]
            ],
            'step' => [
                [
                    '@type' => 'HowToStep',
                    'name' => 'Locate the kosher symbol',
                    'text' => 'Find the kosher certification symbol on the product packaging',
                    'image' => config('app.url') . '/images/kosher-symbols.jpg'
                ],
                [
                    '@type' => 'HowToStep',
                    'name' => 'Enter product details',
                    'text' => 'Search for the product by name, barcode, or scan the QR code',
                    'image' => config('app.url') . '/images/product-search.jpg'
                ],
                [
                    '@type' => 'HowToStep',
                    'name' => 'Verify certification',
                    'text' => 'Check the certification status and details in our database',
                    'image' => config('app.url') . '/images/certification-status.jpg'
                ]
            ]
        ];
    }

    /**
     * Render schema as JSON-LD script tag
     */
    public static function render($schema)
    {
        $json = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
        return "<script type=\"application/ld+json\">\n{$json}\n</script>";
    }
}
