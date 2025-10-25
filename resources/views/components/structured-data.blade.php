@props(['type'])

@if($type === 'Organization')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Geometric Development",
    "description": "Leading Saudi engineering and construction company providing comprehensive solutions in Egypt and Emirates.",
    "url": "{{ config('app.url') }}",
    "logo": "{{ asset('assets/img/logo/favicon.png') }}",
    "address": {
        "@type": "PostalAddress",
        "addressCountry": "EG",
        "addressRegion": "Egypt"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "contactType": "customer service",
        "availableLanguage": ["English", "Arabic"]
    },
    "sameAs": [
        "https://www.facebook.com/GeometricDevelopment",
        "https://www.linkedin.com/company/geometric-development",
        "https://twitter.com/GeometricDev"
    ]
}
</script>
@elseif($type === 'WebSite')
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "WebSite",
    "name": "Geometric Development",
    "url": "{{ config('app.url') }}",
    "potentialAction": {
        "@type": "SearchAction",
        "target": "{{ config('app.url') }}/search?q={search_term_string}",
        "query-input": "required name=search_term_string"
    }
}
</script>
@endif