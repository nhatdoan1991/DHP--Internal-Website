@extends('layouts.app')

@section('content')
<div class="resetPassBody">

    <form method="POST" action="{{ route('password.update') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div style="margin: 1rem;">
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" placeholder="Email" autofocus 
            value="{{ old('email') }}"
            style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
            @error('email')
                <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div style="margin: 1rem;">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" placeholder="Choose a password"
            style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
            @error('password')
                <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div style="margin: 1rem;">
            <label for="password_confirmation" class="sr-only">Password again</label>
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Repeat your password"
             style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
            @error('password_confirmation')
                <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                    {{ $message }}
                </div>
            @enderror
        </div>

        <div style="margin: 1rem;">
                <button class="btn-submit" type="submit">
                Reset Password 
                </button>
        </div>
    </form>
</div>
@endsection('content')
