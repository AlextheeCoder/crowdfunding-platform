<x-adminlayout>
    <div class="table-container">
        <h2>ACTIVE USERS</h2>
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>City</th>
                    <th>Age</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                
                <!-- Row 1 -->
                @foreach($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td><img src="{{$user->profile ? asset('storage/' . $user->profile) : asset('/images/homies.jpg')}}"  alt=""> <span>{{ $user->firstname }} {{ $user->sirname }}</span></td>
                    <td>Madrid</td>
                    <td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->diffInYears(\Carbon\Carbon::now()) : 'N/A' }}</td>
                    <td>{{$user->gender}}</td>
                    <td><a href="{{ route('user.manage', $user->id) }}" class="details-btn">Details</a></td>
                </tr>
                @endforeach
                <!-- Additional rows can be added similarly -->
            </tbody>
        </table>

    </div>
    
</x-adminlayout>

