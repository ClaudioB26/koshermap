{{-- Ejemplo de cómo integrar Schema.org en una vista de producto --}}

@php
    use App\Helpers\SchemaHelper;
@endphp

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - KosherStatus</title>
    
    <!-- Schema.org JSON-LD para SEO -->
    {!! SchemaHelper::productPageSchema($product) !!}
    
    <!-- Meta tags adicionales -->
    <meta name="description" content="{{ Str::limit($product->description ?? $product->name, 160) }}">
    @if($product->image_url)
        <meta property="og:image" content="{{ $product->image_url }}">
    @endif
    <meta property="og:title" content="{{ $product->name }}">
    <meta property="og:description" content="{{ Str::limit($product->description ?? $product->name, 160) }}">
    <meta property="og:type" content="product">
    <meta property="og:url" content="{{ route('products.show', $product->slug) }}">
    
    @if($product->barcode)
        <meta property="product:retailer_item_id" content="{{ $product->barcode }}">
        <meta property="product:availability" content="in stock">
    @endif
</head>
<body>
    <!-- Breadcrumb con microdata -->
    <header>
        {!! SchemaHelper::breadcrumbMicrodata([
            ['name' => 'Home', 'url' => config('app.url')],
            ['name' => 'Products', 'url' => route('products.index')],
            ['name' => $product->name, 'url' => route('products.show', $product->slug)]
        ]) !!}
    </header>

    <!-- Contenido del producto con microdata -->
    <main itemscope itemtype="https://schema.org/Product">
        <div class="product-header">
            <h1 itemprop="name">{{ $product->name }}</h1>
            
            @if($product->brand)
                <div class="brand" itemprop="brand" itemscope itemtype="https://schema.org/Brand">
                    <span itemprop="name">{{ $product->brand->name }}</span>
                </div>
            @endif
            
            @if($product->category)
                <div class="category" itemprop="category">
                    <span>{{ $product->category->name }}</span>
                </div>
            @endif
        </div>

        <div class="product-details">
            @if($product->image_url)
                <div class="product-image">
                    <img src="{{ $product->image_url }}" 
                         alt="{{ $product->name }}" 
                         itemprop="image"
                         loading="lazy">
                </div>
            @endif

            <div class="product-info">
                @if($product->description)
                    <div class="description" itemprop="description">
                        {{ $product->description }}
                    </div>
                @endif

                @if($product->barcode)
                    <div class="barcode">
                        <strong>Barcode:</strong>
                        <span itemprop="gtin13">{{ $product->barcode }}</span>
                    </div>
                @endif

                @if($product->certifier)
                    <div class="certification" itemprop="certification" itemscope itemtype="https://schema.org/Certification">
                        <strong>Certification:</strong>
                        <span itemprop="name">{{ $product->certifier->name }}</span>
                        @if($product->kosher_status)
                            <meta itemprop="certificationStandard" content="Kosher">
                            <div class="kosher-status">
                                <strong>Status:</strong>
                                <span>{{ $product->kosher_status }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                @if($product->countries && $product->countries->count() > 0)
                    <div class="availability">
                        <strong>Available in:</strong>
                        <div itemprop="availableIn" itemscope itemtype="https://schema.org/Country">
                            @foreach($product->countries as $country)
                                <span itemprop="name">{{ $country->name }}</span>{{ !$loop->last ? ', ' : '' }}
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Rating placeholder para futuro sistema de reviews -->
                <div class="rating" itemprop="aggregateRating" itemscope itemtype="https://schema.org/AggregateRating">
                    <meta itemprop="ratingValue" content="4.5">
                    <meta itemprop="reviewCount" content="0">
                    <meta itemprop="bestRating" content="5">
                    <meta itemprop="worstRating" content="1">
                    <span>4.5/5 (0 reviews)</span>
                </div>
            </div>
        </div>

        <!-- Información adicional -->
        <section class="additional-info">
            <h2>Product Details</h2>
            <div class="details-grid">
                <div class="detail-item">
                    <strong>SKU:</strong>
                    <span itemprop="sku">{{ $product->id }}</span>
                </div>
                
                @if($product->source)
                    <div class="detail-item">
                        <strong>Source:</strong>
                        <span>{{ $product->source }}</span>
                    </div>
                @endif
                
                @if($product->created_at)
                    <div class="detail-item">
                        <strong>Added:</strong>
                        <span>{{ $product->created_at->format('Y-m-d') }}</span>
                    </div>
                @endif
            </div>
        </section>

        <!-- Sección de FAQ relacionada -->
        <section class="faq-section">
            <h2>Frequently Asked Questions</h2>
            <div itemscope itemtype="https://schema.org/FAQPage">
                <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                    <h3 itemprop="name">Is this product kosher?</h3>
                    <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            Yes, this product is certified kosher by {{ $product->certifier->name ?? 'a recognized certification agency' }}.
                            @if($product->kosher_status)
                                The kosher status is {{ $product->kosher_status }}.
                            @endif
                        </div>
                    </div>
                </div>
                
                <div itemprop="mainEntity" itemscope itemtype="https://schema.org/Question">
                    <h3 itemprop="name">Where can I buy this product?</h3>
                    <div itemprop="acceptedAnswer" itemscope itemtype="https://schema.org/Answer">
                        <div itemprop="text">
                            This product is available in 
                            @if($product->countries && $product->countries->count() > 0)
                                {{ $product->countries->pluck('name')->implode(', ') }}.
                            @else
                                various locations.
                            @endif
                            Check with local retailers for availability.
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer con información de la organización -->
    <footer>
        <div itemscope itemtype="https://schema.org/Organization">
            <meta itemprop="name" content="KosherStatus">
            <meta itemprop="url" content="{{ config('app.url') }}">
            <p>&copy; {{ date('Y') }} KosherStatus - Comprehensive kosher product database</p>
        </div>
    </footer>
</body>
</html>
