<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Login</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                   
                    <div class="part">
                       
                        <input type="password" id="password" name="password" required placeholder="New Password">
                    </div>
                    <div class="part">
                        <button type="submit" id="create-campaign-button">Reset</button>
                    </div>
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    <input type="hidden" name="token" value="{{ $token }}">
                </form>
            </div>
        </div>
    </div>
</x-layout>