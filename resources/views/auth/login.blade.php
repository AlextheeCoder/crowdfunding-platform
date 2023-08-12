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

                </form>
            </div>
        </div>
    </div>
</x-layout>