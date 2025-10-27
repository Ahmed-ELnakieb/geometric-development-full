<div class="preloader-preview-container" style="position: relative; width: 100%; height: 200px; border-radius: 8px; overflow: hidden; border: 2px solid #e5e7eb;">
    <div class="preview-preloader" style="
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: {{ $backgroundColor ?? '#060606' }};
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
    ">
        <!-- Container for logo and text -->
        <div style="
            display: flex;
            flex-direction: column;
            align-items: center;
        ">
            <!-- Mini Animated Dot (above logo) -->
            <div style="
                width: 6px;
                height: 6px;
                background: linear-gradient(135deg, #C3905F 0%, #D4A574 100%);
                border-radius: 50%;
                box-shadow: 0 0 10px rgba(195, 144, 95, 0.6);
                animation: previewDot 1s ease-in-out infinite;
                margin-bottom: 8px;
            "></div>

            <!-- Logo and Text Wrapper (side by side) -->
            <div style="
                display: flex;
                align-items: center;
                gap: 15px;
            ">
                <!-- Mini Logo (Only show if enabled) -->
                @if($useLogo ?? true)
                    <div style="
                        width: 30px;
                        height: 30px;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        flex-shrink: 0;
                    ">
                        <div style="
                            width: 100%;
                            height: 100%;
                            background: #C3905F;
                            border-radius: 50%;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            font-size: 10px;
                            color: white;
                            font-weight: bold;
                        ">LOGO</div>
                    </div>
                @endif

                <!-- Mini Brand Text (beside logo) -->
                <div style="
                    display: flex;
                    flex-direction: column;
                    align-items: flex-start;
                ">
                    <span style="
                        font-family: Arial, sans-serif;
                        font-size: 14px;
                        font-weight: 800;
                        letter-spacing: 2px;
                        color: #FFFFFF;
                        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3);
                        margin-bottom: 2px;
                    ">{{ $mainText ?? 'GEOMETRIC' }}</span>
                    <span style="
                        font-family: Arial, sans-serif;
                        font-size: 8px;
                        font-weight: 600;
                        letter-spacing: 3px;
                        color: #C3905F;
                        text-shadow: 0 0 8px rgba(195, 144, 95, 0.4);
                    ">{{ $subText ?? 'DEVELOPMENT' }}</span>
                </div>
            </div>
        </div>
    </div>
    
    <div style="
        position: absolute;
        bottom: 8px;
        right: 8px;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 10px;
    ">Preview</div>
</div>

<style>
@keyframes previewDot {
    0%, 100% { transform: translate(-15px, 0); opacity: 0.6; }
    50% { transform: translate(-15px, -10px); opacity: 1; }
}
</style>