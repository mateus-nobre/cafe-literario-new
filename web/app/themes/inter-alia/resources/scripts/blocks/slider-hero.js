/**
 * Slider Hero Block Scripts
 *
 * Inicializa o Swiper para o bloco slider-hero
 */

import domReady from '@roots/sage/client/dom-ready';
import Swiper from 'swiper';
import { Navigation, Pagination, Autoplay, EffectFade } from 'swiper/modules';
import 'swiper/css';
import 'swiper/css/navigation';
import 'swiper/css/pagination';
import 'swiper/css/effect-fade';

domReady(() => {
  const sliderHeroContainers = document.querySelectorAll('.slider-hero');

  sliderHeroContainers.forEach(container => {
    const swiperEl = container.querySelector('.swiper');

    if (!swiperEl) {
      return;
    }

    new Swiper(swiperEl, {
      modules: [Navigation, Pagination, Autoplay, EffectFade],
      loop: true,
      autoplay: {
        delay: 50000,
        disableOnInteraction: false,
      },
      pagination: {
        el: container.querySelector('.swiper-pagination'),
        clickable: true,
      },
      navigation: {
        nextEl: container.querySelector('.swiper-button-next'),
        prevEl: container.querySelector('.swiper-button-prev'),
      },
    });
  });
});
