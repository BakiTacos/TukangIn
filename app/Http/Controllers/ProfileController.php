<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function index()
    {
        $user = auth()->user();
        
        // Contoh pengambilan data pesanan terakhir (jika sudah ada tabel orders)
        // $lastOrder = \App\Models\Order::where('user_id', $user->id)->latest()->first();

        return view('profile.index', compact('user'));
    }

    public function address()
    {
        $user = auth()->user();
        
        // Nanti di sini kamu bisa ambil data dari model Address
        // $addresses = \App\Models\Address::where('user_id', $user->id)->get();
        
        return view('profile.address', compact('user'));
    }

    // app/Http/Controllers/ProfileController.php
    public function storeAddress(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'receiver_name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'full_address' => 'required|string',
            'postal_code' => 'required|string|max:10',
        ]);

        // Jika user menandai sebagai alamat utama, matikan status 'is_primary' pada alamat lain
        if ($request->has('is_primary')) {
            auth()->user()->addresses()->update(['is_primary' => false]);
        }

        auth()->user()->addresses()->create([
            'label' => $request->label,
            'receiver_name' => $request->receiver_name,
            'phone_number' => $request->phone_number,
            'full_address' => $request->full_address,
            'postal_code' => $request->postal_code,
            'note' => $request->note,
            'is_primary' => $request->has('is_primary'),
        ]);

        return back()->with('success', 'Alamat baru berhasil ditambahkan!');
    }

    // app/Http/Controllers/ProfileController.php

// Update Alamat
public function updateAddress(Request $request, $id)
{
    $address = auth()->user()->addresses()->findOrFail($id);
    
    $request->validate([
        'label' => 'required|string|max:50',
        'receiver_name' => 'required|string|max:255',
        'phone_number' => 'required|string',
        'full_address' => 'required|string',
        'postal_code' => 'required|string|max:10',
    ]);

    if ($request->has('is_primary')) {
        auth()->user()->addresses()->where('id', '!=', $id)->update(['is_primary' => false]);
    }

    $address->update($request->all());
    return back()->with('success', 'Alamat berhasil diperbarui!');
}

// Hapus Alamat
public function destroyAddress($id)
{
    $address = auth()->user()->addresses()->findOrFail($id);
    $address->delete();
    
    return back()->with('success', 'Alamat berhasil dihapus!');
}

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
