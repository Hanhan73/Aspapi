// resources/js/gallery.js
// Lightbox galeri (PhotoSwipe v5) — hanya dimuat di halaman galeri via @vite('resources/js/gallery.js').
//
// Markup yang diharapkan:
//   <div data-pswp-gallery>
//     <a href="FULL.webp" data-pswp-width="1600" data-pswp-height="1067" data-cropped="true">
//       <img src="THUMB.webp" width="600" height="400" loading="lazy" alt="">
//       <div class="pswp-caption-content">…caption (sudah di-escape Blade)…</div>
//     </a>
//   </div>

import PhotoSwipeLightbox from 'photoswipe/lightbox';
import PhotoSwipeDynamicCaption from 'photoswipe-dynamic-caption-plugin';
import 'photoswipe/style.css';
import 'photoswipe-dynamic-caption-plugin/photoswipe-dynamic-caption-plugin.css';

function initGalleries() {
    if (!document.querySelector('[data-pswp-gallery]')) return;

    const lightbox = new PhotoSwipeLightbox({
        gallery: '[data-pswp-gallery]',
        children: 'a[data-pswp-width]',
        pswpModule: () => import('photoswipe'),

        // Zoom: pinch (HP), scroll & klik (desktop)
        wheelToZoom: true,
        imageClickAction: 'zoom',
        tapAction: 'toggle-controls',
        doubleTapAction: 'zoom',
        secondaryZoomLevel: 2,
        maxZoomLevel: 4,

        // Counter "3 / 24", panah, swipe — bawaan PhotoSwipe
        indexIndicatorSep: ' / ',
        bgOpacity: 0.94,
        showHideAnimationType: 'zoom',
        padding: { top: 24, bottom: 24, left: 16, right: 16 },

        arrowPrevTitle: 'Sebelumnya',
        arrowNextTitle: 'Berikutnya',
        closeTitle: 'Tutup (Esc)',
        zoomTitle: 'Perbesar',
        errorMsg: 'Foto tidak dapat dimuat.',
    });

    new PhotoSwipeDynamicCaption(lightbox, {
        type: 'below',
        mobileLayoutBreakpoint: 700,
        mobileCaptionOverlapRatio: 0.3,
        // Ambil caption dari elemen tersembunyi di dalam <a> (default selector plugin).
        captionContent: '.pswp-caption-content',
    });

    lightbox.init();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGalleries);
} else {
    initGalleries();
}
