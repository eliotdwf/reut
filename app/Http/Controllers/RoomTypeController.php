<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidDataException;
use App\Models\RoomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class RoomTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roomTypes = RoomType::all();
        info('Fetching all room types', ['count' => $roomTypes->count()]);
        return response()->json($roomTypes);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $label = $request->input('label');
        $bookingPersoAllowed = $request->input('booking_perso_allowed');

        try {
            $this->validateRoomTypeData($label, $bookingPersoAllowed);
            $roomType = RoomType::create([
                'label' => $label,
                'booking_perso_allowed' => $bookingPersoAllowed,
            ]);
            info('Room type created', ['room_type_id' => $roomType->id]);
            return response()->json($roomType, 201);

        } catch (InvalidDataException $e) {
            return response()->json(['error' => $e->getErrorMessage(), 'invalid field' => $e->getInvalidField()], 400);
        } catch (Exception $e) {
            Log::error('Error occurred while creating room type', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while creating room type'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(RoomType $roomType)
    {
        return response()->json($roomType);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, RoomType $roomType)
    {
        $label = $request->input('label');
        $bookingPersoAllowed = $request->input('booking_perso_allowed');

        try {
            $this->validateRoomTypeData($label, $bookingPersoAllowed);
            $roomType->update([
                'label' => $label,
                'booking_perso_allowed' => $bookingPersoAllowed,
            ]);
            info('Room type updated', ['room_type_id' => $roomType->id]);
            return response()->json($roomType);

        } catch (InvalidDataException $e) {
            return response()->json(['error' => $e->getErrorMessage(), 'invalid field' => $e->getInvalidField()], 400);
        } catch (Exception $e) {
            Log::error('Error occurred while updating room type', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while updating room type'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(RoomType $roomType)
    {
        try {
            $roomType->delete();
            info('Room type deleted', ['room_type_id' => $roomType->id]);
            return response()->json(null,204);
        } catch (Exception $e) {
            Log::error('Error occurred while deleting room type', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred while deleting room type'], 500);
        }
    }

    /**
     * Validate the room type data provided.
     * @throws InvalidDataException
     */
    private function validateRoomTypeData($label, $bookingPersoAllowed): void
    {
        if ($label === null || $label === '') {
            throw new InvalidDataException("Label is required", "label");
        }
        if (!is_bool($bookingPersoAllowed)) {
            throw new InvalidDataException("booking_perso_allowed must be a boolean", "booking_perso_allowed");
        }
        if (RoomType::where('label', $label)->exists()) {
            throw new InvalidDataException("Room type with this label already exists", "label");
        }
        if (strlen($label) > 255) {
            throw new InvalidDataException("Label must not exceed 255 characters", "label");
        }
    }
}
