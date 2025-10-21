{{-- Map Section --}}
@php
    $mapSection = $contactPage->sections['map'] ?? [];
    $isActive = $mapSection['is_active'] ?? true;
    $embedUrl = $mapSection['embed_url'] ?? 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3453.0757788567597!2d30.9502945!3d30.0774592!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1458596ebd719e87%3A0xdff54007e2e42e6a!2sGeometric%20Development!5e0!3m2!1sen!2seg!4v1738045355332!5m2!1sen!2seg';
    $height = $mapSection['height'] ?? '450';
@endphp

@if($isActive && $embedUrl)
<!-- map-start -->
<div class="bs-contact-page-map">
    <iframe 
        src="{{ $embedUrl }}" 
        width="600" 
        height="{{ $height }}" 
        style="border:0;" 
        allowfullscreen="" 
        loading="lazy" 
        referrerpolicy="no-referrer-when-downgrade">
    </iframe>
</div>
<!-- map-end -->
@endif
