<section>
    <header>
        <h2 class="text-lg font-bold text-slate-900">
            {{ __('Perbarui Kata Sandi') }}
        </h2>

        <p class="mt-1 text-sm text-slate-500">
            {{ __('Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-5">
        @csrf
        @method('put')

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Kata Sandi Saat Ini') }}</label>
            <input type="password" id="update_password_current_password" name="current_password" autocomplete="current-password"
                class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-3 px-4">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Kata Sandi Baru') }}</label>
            <input type="password" id="update_password_password" name="password" autocomplete="new-password"
                class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-3 px-4">
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">{{ __('Konfirmasi Kata Sandi') }}</label>
            <input type="password" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password"
                class="block w-full rounded-xl border-slate-300 shadow-sm focus:border-[#3890f5] focus:ring-[#3890f5] text-sm py-3 px-4">
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" style="background-color: #3890f5;" class="inline-flex justify-center items-center px-5 py-2.5 rounded-xl border border-transparent font-bold text-xs text-white uppercase tracking-wider shadow-md hover:opacity-90 transition">
                {{ __('Perbarui Sandi') }}
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-xs font-bold text-emerald-600 bg-emerald-50 px-3 py-1.5 rounded-lg"
                >{{ __('Sandi Diperbarui.') }}</p>
            @endif
        </div>
    </form>
</section>