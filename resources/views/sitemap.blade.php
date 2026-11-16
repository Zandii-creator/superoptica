<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ route('home') }}</loc>
    </url>
    <url>
        <loc>{{ route('catalog.index') }}</loc>
    </url>
    @foreach ($products as $product)
        <url>
            <loc>{{ route('products.show', $product) }}</loc>
            @if ($product->updated_at)
                <lastmod>{{ $product->updated_at->toAtomString() }}</lastmod>
            @endif
        </url>
    @endforeach
</urlset>
