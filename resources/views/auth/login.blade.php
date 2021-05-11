<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Raleway" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
    <link rel="stylesheet" href="<?php echo asset('css/nav.css')?>" type="text/css"> 
	<title>DHP Internal | List</title>
    
</head>
    <body>
<div class="loginBody">
                
                <form action="{{ route('login') }}" method="post">
                @csrf
                <div>
                <h2>Delta Hand Pie Internal</h2>
                
                </div>
                    @if (session()->has('status'))
                    <div style="background-color: rgba(239, 68, 68,1); padding: 1rem; border-radius: 0.5rem; color: white; text-align: center;margin: .9rem; ">
						{{ session('status')}}
                    </div>
                    @endif
                <div style="margin: 1rem;">
					<i class="fas fa-user" style="padding: 5px;"></i>
                    <label  class="sr-only" for="email">Email</label>
                    <input type="text" name="email" id="email" placeholder="Email"
                    value="{{ old('email') }}"
                    style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; ">
                    @error('email')
                        <div style="color:red; margin: 0.5rem;font-size: 1em; line-height: 1.25rem; box-sizing: border-box;">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div style="margin: 1rem;">
                    <i class="fas fa-key" style="padding: 5px;"></i>
                    <label for="password" class="sr-only">Password</label>
                    <input type="password" name="password" id="password" placeholder="Password"
                        value="{{ old('password') }}"
                        style="background-color: light-grey; border-width: 2px; width: 100%; padding: 1rem; border-radius: 0.5rem;box-sizing: border-box; padding-left: 20px;padding-bottom: 14px;">
                    @error('password')
                        <div style="color:red; margin: 0.5rem;font-size: 1em; line-height: 1.25rem; box-sizing: border-box;">
                            {{ $message }}
                        </div>
                    @enderror
                    
                <div style="margin: 1rem;">
                    <div style="display:flex; align-items: center;">
                        <input type="checkbox" name="remember" id="remember" style="margin: 0.5rem;">
                        <label for="remember">Remember me</label>
                    </div>       
                </div>
                <div style="margin: 1rem;">
                    <a style="display:block; text-align: center;" href="{{ route('password.request') }}">
                        Forgot Password?
                    </a>       
                </div>
                <div style="margin: 1rem;">
                <button class="btn-submit" type="submit">
                    Login
                </button>
                
                </div>
                </form>
</div>
</body>
</html>

