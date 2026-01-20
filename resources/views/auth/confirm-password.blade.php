<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirm Password - InnoveraOne</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    @keyframes fadeInUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
    .animate-in{animation:fadeInUp .6s ease-out forwards;opacity:0}
    .delay-1{animation-delay:.1s}.delay-2{animation-delay:.2s}
    .glass{background:rgba(15,23,42,.7);backdrop-filter:blur(10px);border:1px solid rgba(168,85,247,.2)}
  </style>
</head>
<body class="antialiased bg-slate-950 min-h-screen flex items-center justify-center p-6">
  <div class="w-full max-w-md">
    <div class="text-center mb-10 animate-in">
      <h1 class="text-4xl font-black bg-gradient-to-r from-purple-300 via-pink-300 to-purple-300 bg-clip-text text-transparent">InnoveraOne</h1>
      <p class="text-gray-400 mt-2">For your security, confirm your password</p>
    </div>

    <div class="glass rounded-3xl p-8 shadow-2xl animate-in delay-1">
      @if ($errors->any())
        <div class="mb-4 p-3 rounded-xl border border-red-500/40 bg-red-500/10 text-red-300 text-sm">
          @foreach ($errors->all() as $error)
            <p>• {{ $error }}</p>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('password.confirm.store') }}" class="space-y-5">
        @csrf
        <div>
          <label class="block text-sm font-bold text-gray-200 mb-2">🔐 Password</label>
          <input type="password" name="password" required class="w-full px-5 py-3.5 rounded-xl bg-slate-700/60 text-white placeholder-gray-400 border-2 border-purple-500/30 focus:border-purple-400 focus:outline-none focus:ring-4 focus:ring-purple-500/20 transition" placeholder="Enter your password" />
        </div>
        <button type="submit" class="w-full py-3.5 bg-gradient-to-r from-purple-500 via-pink-500 to-purple-500 text-white font-bold rounded-xl hover:shadow-2xl hover:shadow-purple-500/50 transition">Confirm</button>
      </form>

      <a href="{{ route('dashboard') }}" class="block text-center text-sm text-purple-300 hover:text-pink-300 mt-4">Cancel</a>
    </div>
  </div>
</body>
</html>

