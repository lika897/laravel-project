@extends('layouts.app')

@section('content')
    <div class="container py-5 text-center">
        <h3>Payment Successful!</h3>
        <p>Thank you for your order.</p>
        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Back to Home</a>
    </div>
@endsection
