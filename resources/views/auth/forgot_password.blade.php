<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Login</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="{{ route('password.email') }}" id="reset-form">
                    @csrf
                    
                    <div class="part">
                        <input type="email" id="email" name="email" required placeholder="Email">
                    </div>
                    <div class="part">
                        <button type="submit" id="reset-button">Reset</button>
                    </div>
                    <div class="notlogin">
                        <span id="reset-message"></span>
                    </div>
                </form>
                
            </div>
        </div>
        @if(session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

    </div>
    <script>
        document.getElementById('reset-form').addEventListener('submit', function(e) {
            var button = document.getElementById('reset-button');
            var message = document.getElementById('reset-message');
            
            // Disable the button
            button.disabled = true;
            
            // Change the button text
            button.innerText = 'Sending...';
        });
    </script>
    
</x-layout>