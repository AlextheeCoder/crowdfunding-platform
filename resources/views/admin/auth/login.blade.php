<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Admin Login</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="/admin/authenticate">
                    @csrf
                    <div class="part">
                        
                        <input type="email" id="email" name="email" required placeholder="Email">
                    </div>
                    <div class="part">
                       
                        <input type="password" id="password" name="password" required placeholder="Password">
                    </div>
                    <div class="part">
                        <button style="background-color:#0C5FCD" type="submit" id="create-campaign-button">Login</button>
                    </div>
                    <div class="notlogin">
                        <span>Login as regular user <a href="/login" alt=#>Login</a></span>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layout>