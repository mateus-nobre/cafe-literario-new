@extends('layouts.app')

@section('content')
  <div class="search-results">
    @if (have_posts())
      <h1>{{ __('Search Results', 'sage') }}</h1>
      @while (have_posts())
        @php(the_post())
        <article>
          <h2><a href="{{ get_permalink() }}">{{ get_the_title() }}</a></h2>
          {!! apply_filters('the_excerpt', get_the_excerpt()) !!}
        </article>
      @endwhile
    @else
      <h1>{{ __('No results found', 'sage') }}</h1>
      <p>{{ __('Try a different search term.', 'sage') }}</p>
    @endif
  </div>
@endsection

