<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // GET /api/rooms (Public: List all rooms)
    public function index()
    {
        return response()->json(Room::all());
    }

    // GET /api/rooms/{id} (Public: View single room details)
    public function show(string $id)
    {
        $room = Room::findOrFail($id);
        return response()->json($room);
    }

    // POST /api/rooms (Admin only: Add a new room)
    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Admins only'], 403);
        }

        $validated = $request->validate([
            'room_name' => 'required|string|max:255',
            'room_type' => 'required|string|max:100',
            'description' => 'required|string',
            'price_per_night' => 'required|numeric|min:0',
            'max_guests' => 'required|integer|min:1',
            'room_status' => 'boolean',
        ]);

        $room = Room::create($validated);

        return response()->json($room, 201);
    }

    // PUT /api/rooms/{id} (Admin only: Edit a room)
    public function update(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Admins only'], 403);
        }

        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'room_name' => 'sometimes|string|max:255',
            'room_type' => 'sometimes|string|max:100',
            'description' => 'sometimes|string',
            'price_per_night' => 'sometimes|numeric|min:0',
            'max_guests' => 'sometimes|integer|min:1',
            'room_status' => 'sometimes|boolean',
        ]);

        $room->update($validated);

        return response()->json($room);
    }

    // DELETE /api/rooms/{id} (Admin only: Delete a room)
    public function destroy(Request $request, string $id)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Forbidden: Admins only'], 403);
        }

        $room = Room::findOrFail($id);
        $room->delete();

        return response()->json(['message' => 'Room deleted successfully']);
    }
}