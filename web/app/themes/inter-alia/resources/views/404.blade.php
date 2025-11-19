@extends('layouts.app')

@section('content')
  <div class="error-404">
    <h1>{{ __('Page Not Found', 'sage') }}</h1>
    <p>{{ __('Sorry, but the page you are looking for has not been found.', 'sage') }}</p>
    <a href="{{ home_url('/') }}">{{ __('Return to homepage', 'sage') }}</a>
  </div>
@endsection

