<footer class="bs-footer-4-area wa-p-relative wa-fix pt-130" style="background-color: {{ settings('footer_bg_color', '#1a1a1a') }}; color: {{ settings('footer_text_color', '#ffffff') }};">
    <div class="bs-footer-4-bg wa-fix">
        <img src="{{ asset(settings('footer_bg_image', 'assets/img/footer/f4-bg-1.png')) }}" alt="Footer Background">
    </div>

    <div class="container bs-container-2">
        <div class="bs-footer-4-wrap mb-125">
            <!-- left-side -->
            <div class="bs-footer-4-left">
                <a href="{{ route('home') }}" aria-label="name" class="bs-footer-4-logo">
                    <img src="{{ asset(settings('logo_light', 'assets/img/logo/logo_light.png')) }}" alt="{{ settings('site_name', 'Geometric Development') }}" style="width: 39%;">
                </a>

                <div class="bs-footer-4-widget">
                    <!-- single-widget: Main Links (order 1-19) -->
                    <div class="bs-footer-4-widget-single">
                        <ul class="bs-footer-4-menu wa-list-style-none">
                            @foreach(getFooterMenu()->whereNull('parent_id')->where('order', '<', 20) as $menuItem)
                            <li>
                                <a href="{{ getMenuUrl($menuItem) }}" 
                                   aria-label="name"
                                   @if($menuItem->open_in_new_tab) target="_blank" @endif>
                                    @if($menuItem->icon)
                                        <i class="{{ $menuItem->icon }}"></i>
                                    @endif
                                    {{ $menuItem->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- single-widget: Featured Projects (order 20-49) -->
                    <div class="bs-footer-4-widget-single">
                        <ul class="bs-footer-4-menu wa-list-style-none">
                            @foreach(getFooterMenu()->whereNull('parent_id')->whereBetween('order', [20, 49]) as $menuItem)
                            <li>
                                <a href="{{ getMenuUrl($menuItem) }}" 
                                   aria-label="name"
                                   @if($menuItem->open_in_new_tab) target="_blank" @endif>
                                    @if($menuItem->icon)
                                        <i class="{{ $menuItem->icon }}"></i>
                                    @endif
                                    {{ $menuItem->title }}
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

            <!-- right -->
            <div class="bs-footer-4-contact">
                <h4 class="bs-h-1 title wa-split-up wa-capitalize wa-fix">{{ settings('footer_contact_title', 'contact us') }}</h4>
                <div class="bs-footer-4-contact-link">
                    @if(settings('contact_email'))
                        <p class="bs-p-4 link-title">{{ settings('contact_email_label', 'Email') }}</p>
                        <a href="mailto:{{ settings('contact_email') }}" target="_blank" class="link-elm bs-p-4 wa-clip-left-right">{{ settings('contact_email') }}</a>
                    @endif

                    @if(settings('phone_1') || settings('phone_2'))
                        <p class="bs-p-4 link-title">{{ settings('contact_phone_label', 'phone') }}</p>
                        @if(settings('phone_1'))
                            <a href="https://wa.me/{{ settings('phone_1_whatsapp') }}" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">{{ settings('phone_1') }}</a>
                        @endif
                        @if(settings('phone_2'))
                            <a href="https://wa.me/{{ settings('phone_2_whatsapp') }}" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">{{ settings('phone_2') }}</a>
                        @endif
                    @endif

                    @if(settings('address'))
                        <p class="bs-p-4 link-title">{{ settings('contact_address_label', 'Address') }}</p>
                        <a href="{{ settings('address_map_url', '#') }}" target="_blank" class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">{{ settings('address') }}</a>
                    @endif

                    @php
                        $additionalContactItems = json_decode(settings('additional_contact_items', '[]'), true) ?: [];
                    @endphp
                    @foreach($additionalContactItems as $item)
                        @if(!empty($item['is_active']) && !empty($item['label']) && !empty($item['value']))
                            <p class="bs-p-4 link-title">{{ $item['label'] }}</p>
                            @if(!empty($item['link_url']))
                                <a href="{{ $item['link_url'] }}" 
                                   target="{{ !empty($item['open_in_new_tab']) ? '_blank' : '_self' }}" 
                                   class="link-elm bs-p-4 wa-clip-left-right" 
                                   style="display: block;">{{ $item['value'] }}</a>
                            @else
                                <p class="link-elm bs-p-4 wa-clip-left-right" style="display: block;">{{ $item['value'] }}</p>
                            @endif
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="bs-footer-4-copyright">
        <div class="container bs-container-2">
            <div class="bs-footer-4-copyright-flex">
                <p class="bs-p-4 bs-footer-4-copyright-text">
                    &copy;{{ date('Y') }}, <a href="#">{{ settings('footer_copyright_text', 'Geometric-Development') }}</a> All Rights Reserved.
                </p>

                <div class="bs-footer-4-copyright-social">
                    @php
                        $socialLinks = getFooterMenu()->whereNotNull('parent_id')->where('order', '>=', 50);
                    @endphp
                    @foreach($socialLinks as $social)
                        <a href="{{ getMenuUrl($social) }}" 
                           aria-label="name" 
                           class="elm-link bs-p-4"
                           @if($social->open_in_new_tab) target="_blank" @endif>
                            @if($social->icon)
                                <i class="{{ $social->icon }}"></i>
                            @endif
                            {{ strtolower($social->title) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</footer>
