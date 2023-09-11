<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Register</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="/users" enctype="multipart/form-data">
                    @csrf
                    <div class="part">
                        
                        <input type="text" id="name" name="name" required placeholder="Name" value="{{old('name')}}">
                    </div>
                    <div class="part">
                        
                        <input type="email" id="email" name="email" required placeholder="Email" value="{{old('email')}}">
                        @error('email')
                        <p style="font-size: 12px; color: red; margin-top: 8px; align-self:center;">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="part">
                        <label for="image">Profile</label>
                        <input type="file" id="profile" name="profile" required>
                      </div>
                    <div class="part">
                        
                        <input type="password" id="password" name="password" required placeholder="Password">
                        @error('password')
                        <p style="font-size: 12px; color: red; margin-top: 8px; align-self:center;">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="part">
                        <button type="submit" id="create-campaign-button">Register</button>
                    </div>
                    <div class="notlogin">
                        <span>Already have an account <a href="/login" alt=#>Login</a></span>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-layout>