<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // GET /api/bookings (Authenticated: Admin sees all, Guest sees own)
    public function index(Request $request)
    {
        if ($request->user()->role === 'admin') {
            $bookings = Booking::with(['user', 'room'])->latest('booking_date')->get();
        } else {
            $bookings = Booking::with('room')
                ->where('user_id', $request->user()->user_id)
                ->latest('booking_date')
                ->get();
        }

        return response()->json($bookings);
    }

    // POST /api/bookings (Authenticated Guest/Admin: Create a reservation)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,room_id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = Room::findOrFail($validated['room_id']);

        if (!$room->room_status) {
            return response()->json(['message' => 'This room is currently unavailable.'], 422);
        }

        // Prevent double-booking: check if an active booking overlaps these dates
        $overlap = Booking::where('room_id', $room->room_id)
            ->where('booking_status', '!=', 'Cancelled')
            ->where(function ($query) use ($validated) {
                $query->where('check_in', '<', $validated['check_out'])
                      ->where('check_out', '>', $validated['check_in']);
            })
            ->exists();

        if ($overlap) {
            return response()->json([
                'message' => 'Room is already booked for the selected dates.'
            ], 422);
        }

        // Calculate nights and total_amount server-side (never trust frontend price math)
        $nights = Carbon::parse($validated['check_in'])
            ->diffInDays(Carbon::parse($validated['check_out']));
        $totalAmount = $nights * $room->price_per_night;

        $booking = Booking::create([
            'user_id' => $request->user()->user_id,
            'room_id' => $room->room_id,
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'total_amount' => $totalAmount,
            'booking_status' => 'Pending',
        ]);

        return response()->json($booking->load('room'), 201);
    }

    // GET /api/bookings/{id}
    public function show(Request $request, string $id)
    {
        $booking = Booking::with(['user', 'room'])->findOrFail($id);

        if ($request->user()->role !== 'admin' && $booking->user_id !== $request->user()->user_id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json($booking);
    }

    // PUT /api/bookings/{id} (Admin: Confirm/Cancel; Guest: Cancel own)
    public function update(Request $request, string $id)
    {
        $booking = Booking::findOrFail($id);
        $user = $request->user();

        $validated = $request->validate([
            'booking_status' => 'required|in:Pending,Confirmed,Cancelled',
        ]);

        if ($user->role !== 'admin') {
            if ($booking->user_id !== $user->user_id || $validated['booking_status'] !== 'Cancelled') {
                return response()->json(['message' => 'Guests can only cancel their own bookings.'], 403);
            }
        }

        $booking->update(['booking_status' => $validated['booking_status']]);

        return response()->json($booking->load('room'));
    }

    // DELETE /api/bookings/{id} (Admin only)
    public function destroy(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Admins only'], 403);
        }

        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(['message' => 'Booking deleted successfully']);
    }
}