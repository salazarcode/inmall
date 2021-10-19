@extends('layouts.app')

@section('content')
  @include('partials.page-header')
  <div class="container has-padding-top-50">
    <div class="content tarjetas-hero has-background-white font-w is-500 has-padding-40 has-text-primary" data-scroll data-scroll-speed="1" data-aos>
      @while(have_posts()) @php(the_post())
        @includeFirst(['partials.content-page', 'partials.content'])
      @endwhile
    </div>
  </div>
@endsection
