@extends('layouts.app')

@section('content')
<div class="forgotPassBody">  
            <form action="{{ route('password.email') }}" method="post">
            @csrf
            
            <div style="margin: 1rem;">
                <p class="forgotPassPrompt">
                    Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                </p>    
            </div>
            
            <div style="margin: 1rem;">
                <label for="email" class="sr-only">Email</label>
                <input type="email" name="email" id="email" placeholder="Email"
                value="{{ old('email') }}"
                style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
                @error('email')
                    <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div style="margin: 1rem;">
                <button class="btn-submit" type="submit">
                Email Password Reset Link
                </button>
            </div>
            </form>
    </div>

@endsection('content')