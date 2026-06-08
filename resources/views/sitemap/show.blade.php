<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    @foreach($items as $item)
    <url>
        @if($type === 'pages')
            <loc>{{ url($item->slug) }}</loc>
        @else
            <loc>{{ route($route, $item->slug) }}</loc>
        @endif
        <lastmod>{{ $item->updated_at->toAtomString() }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>
