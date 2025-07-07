<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Login API - Blade</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('loginForm');
        const resultDiv = document.getElementById('result');

        form.addEventListener('submit', async function(e) {
          e.preventDefault();

          const email = document.getElementById('login').value;
          const password = document.getElementById('password').value;

          try {
            const response = await fetch('/api/login', {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
              },
              body: JSON.stringify({ email, password }),
            });

            const data = await response.json();

            if (response.ok) {
              resultDiv.innerHTML = `<p style="color:green">Login berhasil!</p>`;
              console.log('Token:', data.data.token);
              // Simpan token ke localStorage/sessionStorage jika perlu
              localStorage.setItem('api_token', data.data.token);

              // Redirect contoh
              window.location.href = '/dashboard';
            } else {
              resultDiv.innerHTML = `<p style="color:red">${data.message || 'Login gagal'}</p>`;
            }
          } catch (error) {
            resultDiv.innerHTML = `<p style="color:red">Terjadi kesalahan. Coba lagi.</p>`;
          }
        });
      });
    </script>

    <style>
      /* Tambahkan CSS sesuai desain Tailwind-mu jika perlu */
    </style>
</head>
<body>
    <div>
      <div class="min-h-screen flex flex-col items-center justify-center py-6 px-4 bg-gray-100 dark:bg-gray-900">
        <div class="max-w-md w-full">
          <a href="javascript:void(0)">
            <img src="https://readymadeui.com/readymadeui.svg" alt="logo" class="w-40 mb-8 mx-auto block dark:invert" />
          </a>

          <div class="p-8 rounded-2xl bg-white dark:bg-gray-800 shadow">
            <h2 class="text-slate-900 dark:text-white text-center text-3xl font-semibold">Sign in</h2>

            <form id="loginForm" class="mt-12 space-y-6">
              <label class="text-slate-800 dark:text-slate-200 text-sm font-medium mb-2 block">
                Username / Email
              </label>
              <div class="relative flex items-center">
                <input id="login" name="login" type="text" required
                  class="w-full text-slate-800 dark:text-white bg-white dark:bg-gray-700 text-sm border border-slate-300 dark:border-gray-600 px-4 py-3 rounded-md outline-blue-600"
                  placeholder="Enter username or email" autofocus />
              </div>

              <div>
                <label class="text-slate-800 dark:text-slate-200 text-sm font-medium mb-2 block">Password</label>
                <div class="relative flex items-center">
                  <input id="password" name="password" type="password" required
                    class="w-full text-slate-800 dark:text-white bg-white dark:bg-gray-700 text-sm border border-slate-300 dark:border-gray-600 px-4 py-3 rounded-md outline-blue-600"
                    placeholder="Enter password" />
                </div>
              </div>

              <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center">
                  <input id="remember-me" name="remember-me" type="checkbox"
                    class="h-4 w-4 shrink-0 text-blue-600 focus:ring-blue-500 border-slate-300 dark:border-gray-600 rounded" />
                  <label for="remember-me" class="ml-3 block text-sm text-slate-800 dark:text-slate-200">
                    Remember me
                  </label>
                </div>
              </div>

              <div class="!mt-12">
                <button type="submit"
                  class="w-full py-2 px-4 text-[15px] font-medium tracking-wide rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none">
                  Sign in
                </button>
              </div>
            </form>

            <div id="result" class="mt-4 text-center"></div>
          </div>
        </div>
      </div>
    </div>
</body>
</html>
