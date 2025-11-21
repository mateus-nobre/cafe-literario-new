@unless ($block->preview)
    <div {{ $attributes->merge(['class' => 'slider-hero']) }}>
    @endunless

    @if ($items)
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach ($items as $item)
                    <div class="swiper-slide">
                        <a href="{{ $item['url'] }}">
                            <div class="slide-image"
                                style="background-image: url('{{ $item['featured_image'] }}')">
                                <div class="slide-content">
                                    <h3>{{ $item['title'] }}</h3>
                                    @if ($item['excerpt'])
                                        <p>{!! $item['excerpt'] !!}</p>
                                    @endif
                                    <span class="btn slide-content-button" href="#">Ler mais</span>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
            <div class="swiper-pagination"></div>
        </div>
    @else
        <p>{{ $block->preview ? 'Escolha um post como destaque...' : 'Sem posts selecionados para destaque' }}</p>
    @endif

    @unless ($block->preview)
    </div>
@endunless
