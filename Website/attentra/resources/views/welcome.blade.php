@extends('layouts.Master')

@section('content')
    <section id="book">
        <div class="container">
            @include('masterSection.book')
        </div>
    </section>

    <section class="call-to-action">
        <div class="container">
            @include('masterSection.callToAction')
        </div>
    </section>

    <section id="reviews" class="reviews">
        <div class="container">
            @include('masterSection.review')
        </div>
    </section>

    <section id="author">
        <div class="container">
            @include('masterSection.author')
        </div>
    </section>

    <section class="call-to-action">
        <div class="container">
            @include('masterSection.freeInstanceBookRecive')
        </div>
    </section>

    <section id="sample-form">
        <div class="container">
            @include('masterSection.sampleForm')
        </div>
    </section>

    <section id="contact">
        <div class="container">
            {!! $content !!}
        </div>
    </section>
    <section id="logintoProfileSection">
    </section>

@endsection

