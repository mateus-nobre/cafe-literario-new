@extends('layouts.app')

@section('content')
  @if (have_posts())
    @while (have_posts())
      @php(the_post())
      {!! apply_filters('the_content', get_the_content()) !!}
    @endwhile
  @endif
@endsection

