@extends('layouts.guest')

@section('content')
    @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="form-group">
                <input type="email" name="email" placeholder="Email Akademik" class="form-control @error('email') is-invalid @enderror" required autofocus oninvalid="if(this.validity.valueMissing){this.setCustomValidity('Harap isi bidang ini')}else if(this.validity.typeMismatch){this.setCustomValidity('Masukkan alamat email yang valid, harus mengandung karakter \'@\'')}" oninput="this.setCustomValidity('')">
                <span class="icon">
                    <i class="fas fa-envelope"></i>
                </span>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <div class="form-group position-relative">
                <input type="password" name="password" id="password" placeholder="Password" class="form-control @error('password') is-invalid @enderror" required oninvalid="this.setCustomValidity('Harap isi bidang ini')" oninput="this.setCustomValidity('')">
                <span class="icon toggle-password" onclick="togglePasswordVisibility()">
                    <i class="fas fa-eye"></i>
                </span>
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            

            <button type="submit" class="submit-button w-100">
                Masuk Sistem
            </button>
        </form>

<script>
    function togglePasswordVisibility() {
        const passwordField = document.getElementById('password');
        const toggleIcon = document.querySelector('.toggle-password i');

        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            toggleIcon.classList.remove('fa-eye');
            toggleIcon.classList.add('fa-eye-slash');
        } else {
            passwordField.type = 'password';
            toggleIcon.classList.remove('fa-eye-slash');
            toggleIcon.classList.add('fa-eye');
        }
    }
</script>
@endsection