<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="description" content="Visual Job">
    <meta name="author" content="Carlos Espinoza">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="shortcut icon" href="/favicons/favicon.ico">
    <title>Vendor Login</title>
</head>
<body>
    <center>
        <p>Suppliers Access</p>
    </center>
    <x-guest-layout>
        <x-jet-authentication-card>
            <x-slot name="logo">
                
            </x-slot>
    
            <x-jet-validation-errors class="mb-4" />
    
            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif
                <div>
                    <img src="/img/Logo-web-1.png" width="600px" alt="Company Logo">
                </div>
                <br>
            <form method="POST" action="/vendors/access/api/login">
                @csrf
    
                <div>
                    <x-jet-label style="font-family: 'Prompt', sans-serif;" for="email" value="{{ __('User') }}" />
                    <x-jet-input id="email" class="block mt-1 w-full" type="text" name="email" :value="old('email')" required autofocus />
                </div>
    
                <div class="mt-4">
                    <x-jet-label style="font-family: 'Prompt', sans-serif; font-size: 14px" for="password" value="{{ __('Password') }}" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>
    
                <div class="flex items-center justify-end mt-4">
                    <x-jet-button class="ml-4">
                        {{ __('Log in') }}
                    </x-jet-button>
                </div>
            </form>
        </x-jet-authentication-card>
    </x-guest-layout>
</body>
</html>
