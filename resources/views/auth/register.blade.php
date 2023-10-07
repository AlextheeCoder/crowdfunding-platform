<x-layout>
    <div class="auth">
        <div class="container" style="height: 550px;">
            <div class="heading">
                <h2>Register</h2>
            </div>
            <div class="formcont">
                <form method="POST" action="/users" enctype="multipart/form-data">
                    @csrf
                    <div class="part">
                        
                        <input type="text" id="name" name="firstname" required placeholder="First Name" value="{{old('firstname')}}">
                    </div>
                    <div class="part">
                        
                        <input type="text" id="name" name="sirname" required placeholder="Sir Name" value="{{old('sirname')}}">
                    </div>
                    <div class="part">
                        <select name="gender" id="gender" style=" width: 100%; padding: 10px; border: 1px solid #ccc;border-radius: 4px; font-size: 16px;transition: border-color 0.3s;">
                          <option value="male">Male</option>
                          <option value="female">Female</option>
                          <option value="other">Other</option>
                        </select>
                      </div>
                      <div class="part">
                        <label for="date">Date of Birth: </label>
                        <input type="date" id="dob" name="dob" required style="width: 97%; padding: 10px; border: 1px solid #ccc;border-radius: 4px; font-size: 16px;transition: border-color 0.3s;">
                      </div>
                    <div class="part">
                        
                        <input type="email" id="email" name="email" required placeholder="Email" value="{{old('email')}}">
                        @error('email')
                        <p style="font-size: 12px; color: red; margin-top: 8px; align-self:center;">{{$message}}</p>
                        @enderror
                    </div>
                    <div class="part">
                        <label for="image">Profile: </label>
                        <input type="file" id="profile" name="profile" required style="width: 97%; padding: 10px; border: 1px solid #ccc;border-radius: 4px; font-size: 16px;transition: border-color 0.3s;">
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