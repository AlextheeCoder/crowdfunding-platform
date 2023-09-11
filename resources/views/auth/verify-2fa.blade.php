<x-layout>
    <div class="auth">
        <div class="container">
            <div class="heading">
                <h2>Verify OTP</h2>
                <h5 style="color: gray">We have sent an OTP to your email address</h5>
            </div>
            <div class="formcont">
                <form method="POST" action="/verify-registration-otp">
                    @csrf
                    <div class="part">
                        
                        <input type="text" id="otp" name="otp" required placeholder="OTP">
                    </div>
                    
                    <div class="part">
                        <button type="submit" id="create-campaign-button">Submit</button>
                    </div>
                    <div class="notlogin">
                        <span>Didn't Receive a code<a href="{{ route('resendRegOtp') }}" alt="Resend OTP">Resend</a></span>
                    </div>
                     <!-- Countdown Timer -->
                     <div id="countdown" style="font-size: 15px; color: red; margin-top: 10px; align-self:center;"></div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Set the time we're counting down to (2 minutes from now)
        var countDownDate = new Date().getTime() + 120 * 1000;

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get the current date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Calculate minutes and seconds
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="countdown"
            document.getElementById("countdown").innerHTML = "OTP expires in: " + minutes + "m " + seconds + "s ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("countdown").innerHTML = "OTP expired";
            }
        }, 1000);
    </script>
</x-layout>