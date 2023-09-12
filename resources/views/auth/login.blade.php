<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Login</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="/users/authenticate">
                    @csrf
                    <div class="part">
                        
                        <input type="email" id="email" name="email" required placeholder="Email">
                    </div>
                    <div class="part">
                       
                        <input type="password" id="password" name="password" required placeholder="Password">
                    </div>
                    <div class="part">
                        <button type="submit" id="create-campaign-button">Login</button>
                    </div>
                    <div class="notlogin">
                        <span>Don't have an accornt <a href="/register" alt=#>Register</a></span>
                    </div>
                    @if(session('showResetLink'))
                    <div class="notlogin">
                        <span>Forgot password? <a href="{{ route('password.request') }}"> Click here to reset</a></span>
                    </div>
                @endif
                </form>
            </div>
        </div>
    </div>
</x-layout>