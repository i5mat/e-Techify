@csrf
<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input name="name" type="text" class="form-control rounded-pill @error('name') is-invalid @enderror" id="name" aria-describedby="name"
           value="{{ old('name') }}@isset($user){{ $user->name }}@endisset">
    @error('name')
    <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
    @enderror
</div>
<div class="mb-3">
    <label for="email" class="form-label">Email address</label>
    <input name="email" type="email" class="form-control rounded-pill @error('email') is-invalid @enderror" id="email" aria-describedby="email"
           value="{{ old('email') }}@isset($user){{ $user->email }}@endisset">
    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
    @error('email')
    <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
    @enderror
</div>
@isset($create)
<div class="mb-3">
    <label for="password" class="form-label">Password</label>
    <input name="password" type="password" class="form-control @error('password') is-invalid @enderror" id="password" value="{{ old('password') }}">
    @error('password')
    <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
    @enderror
</div>
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Confirm Password</label>
    <input name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" value="{{ old('password') }}">
    @error('password_confirmation')
    <span class="invalid-feedback" role="alert">
                    {{ $message }}
                </span>
    @enderror
</div>
@endisset
<div class="mb-3">
    @foreach($roles as $role)
        <div class="form-check">
            <input class="form-check-input" name="roles[]"
                   type="checkbox" value="{{ $role->id }}" id="{{ $role->name }}"
            @isset($user) @if(in_array($role->id, $user->roles->pluck('id')->toArray())) checked @endif @endisset>
            <label class="form-check-label" for="{{ $role->name }}">
                {{ $role->name }}
            </label>
        </div>
    @endforeach
</div>
<button type="submit" class="btn btn-primary" style="width: 100%">Submit</button>
