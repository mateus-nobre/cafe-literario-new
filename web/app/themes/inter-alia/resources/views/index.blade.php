@extends('layouts.app')

@section('content')
  <div class="content-with-sidebar">
    <div class="content-with-sidebar__wrapper">
      <main class="content-with-sidebar__main">
        @if (have_posts())
          @while (have_posts())
            @php(the_post())
            {!! apply_filters('the_content', get_the_content()) !!}
          @endwhile
        @endif
      </main>

      <aside class="content-with-sidebar__sidebar">
        @include('partials.sidebar')
      </aside>
    </div>
  </div>
@endsection

