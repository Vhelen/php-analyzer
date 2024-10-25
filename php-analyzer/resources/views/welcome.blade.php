@extends('layouts.app')

@section('title', 'Home Page')

@section('content')
<div class="container col-xl-10 col-xxl-8 px-4 py-5">
    <div class="row align-items-center g-lg-5 py-5">
        <div class="col-lg-7 text-center text-lg-start">
            <h1 class="display-4 fw-bold lh-1 text-body-emphasis mb-3">Welcome to PHP analyzer!</h1>
            <p class="col-lg-10 fs-4">Below is an example form built entirely with Bootstrap’s form controls. Each required form group has a validation state that can be triggered by attempting to submit the form without completing it.</p>
        </div>
        <div class="col-md-10 mx-auto col-lg-5">
            <form class="p-4 p-md-5 border rounded-3 bg-body-tertiary" method="POST" action="{{ route('analyze') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-floating mb-3 file-drop-area">
                    <input type="file" id="directory" name="directory[]" webkitdirectory directory>
                </div>

                <button class="w-100 btn btn-lg btn-primary" type="submit">analyze</button>
                <hr class="my-4">

                <small class="text-body-secondary">Dev by 🐱 with ❤️</small>
            </form>
        </div>
    </div>
</div>
@endsection
