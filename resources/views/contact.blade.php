@extends('layouts.app')

@section('title', ($contactPage->meta_title ?? $contactPage->title ?? 'Contact Us') . ' - Geometric Development')

@section('body-class', '')

@section('content')

{{-- Contact page data is passed from ContactController --}}
{{-- Include all contact page sections --}}
@include('pages.contact.breadcrumb')
@include('pages.contact.contact-info')
@include('pages.contact.contact-form')
@include('pages.contact.map')

@endsection
