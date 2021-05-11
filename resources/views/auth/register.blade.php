@extends('layouts.app')

@section('content')
    <div class="registrationBody">  
        
            <form action="{{ route('register') }}" method="post">
            @csrf
            <div style="margin: 1rem;">
                <label  class="sr-only" for="firstname">First Name</label>
                <input type="text" name="firstname" id="firstname" placeholder="First name"
                value="{{ old('firstname') }}"
                style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
                @error('firstname')
                    <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div style="margin: 1rem;">
                <label  class="sr-only" for="lastname">Last Name</label>
                <input type="text" name="lastname" id="lastname" placeholder="Last name"
                value="{{ old('lastname') }}"
                style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
                @error('lastname')
                    <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                        {{ $message }}
                    </div>
                @enderror
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
                <label for="phonenumber" class="sr-only">Phone Number</label>
                <input type="tel" name="phonenumber" id="phonenumber" placeholder="Phone Number"
                value="{{ old('phonenumber') }}"
                style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
                @error('phonenumber')
                    <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @if (Auth::check() && Auth::user()->id == 1)
            <div style="margin: 1rem;">
                <select class="form-control" name="role" required="required"
                style="background-color: light-grey; border-width: 2px; width: 100%; padding: .5rem; border-radius: 0.5rem;box-sizing: border-box; ">
                    <option value="driver">Driver</option>
                    <option value="operator">Operator</option>
                    
                </select>
                @error('phonenumber')
                    <div style="color:red; margin: 0.5rem;font-size: 0.875rem; line-height: 1.25rem; box-sizing: border-box;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            @else
            <input name="role" type="hidden" value="driver">
            @endif
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
                <button class="btn-submit" type="submit" >
                Register
                </button>
            </div>
            </form>
    </div>

@endsection('content')


