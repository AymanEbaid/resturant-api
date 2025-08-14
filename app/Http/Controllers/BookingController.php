<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use App\Notifications\BookingStatusNotification;
use App\Notifications\NewBookingNotification;
use App\Trait\apiresponse;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    use apiresponse;

    // للأدمن فقط: عرض كل الحجوزات مع بيانات المستخدمين
    public function allBookings(Request $request)
    {
        if (!$request->user()->isAdmin()) {
            return $this->error('Unauthorized', 403);
        }

        $bookings = Booking::with('user')->get();
        return $this->success($bookings, 'Bookings retrieved successfully');
    }

    // للمستخدم: عرض حجوزاته فقط
    public function myBookings(Request $request)
    {
        $bookings = Booking::where('user_id', $request->user()->id)->get();
        return $this->success($bookings, 'My bookings retrieved successfully');
    }

    // إنشاء حجز مرتبط بالمستخدم الحالي، والحالة تبدأ "pending"
    public function store(Request $request)
    {
        $data = $request->validate([
            'reservation_date' => 'required|date',
            'reservation_time' => 'required|date_format:H:i',
            'number_of_guests' => 'required|integer|min:1',
            'name' => 'required|string|max:255',
            'contact_number' => 'nullable|string|max:15',
        ]);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'pending';

        $booking = Booking::create($data);
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            $admin->notify(new NewBookingNotification($booking));
        }
        return $this->success($booking, 'Booking created successfully.', 201);
    }

    // عرض تفاصيل حجز - يتأكد أن المستخدم صاحب الحجز أو أدمن
    public function show(Request $request, Booking $booking)
    {
        if (!$request->user()->isAdmin() && $booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        return $this->success($booking, 'Booking retrieved successfully.');
    }

    // تحديث الحجز - المستخدم يعدل بياناته فقط، الأدمن يقدر يعدل الحالة
    public function update(Request $request, Booking $booking)
    {
        $user = $request->user();

        if (!$user->isAdmin() && $booking->user_id !== $user->id) {
            return $this->error('Unauthorized', 403);
        }

        $rules = [
            'reservation_date' => 'sometimes|date',
            'reservation_time' => 'sometimes|date_format:H:i',
            'number_of_guests' => 'sometimes|integer|min:1',
            'name' => 'sometimes|string|max:255',
            'contact_number' => 'nullable|string|max:15',
        ];

        if ($user->isAdmin()) {
            $rules['status'] = 'sometimes|in:pending,confirmed,cancelled';
        }

        $data = $request->validate($rules);

        if (!$user->isAdmin()) {
            unset($data['status']);
        }

        $booking->update($data);

        return $this->success($booking, 'Booking updated successfully.');
    }

    // حذف الحجز - فقط مالك الحجز أو الأدمن
    public function destroy(Request $request, Booking $booking)
    {
        if (!$request->user()->isAdmin() && $booking->user_id !== $request->user()->id) {
            return $this->error('Unauthorized', 403);
        }

        $booking->delete();

        return $this->success(null, 'Booking deleted successfully.');
    }


    public function confirm(Request $request, Booking $booking)
    {


        $booking->update(['status' => 'confirmed']);
        $booking->user->notify(new BookingStatusNotification($booking));



        return $this->success($booking, 'Booking confirmed successfully.');
    }

    public function reject(Request $request, Booking $booking)
    {



        $booking->update(['status' => 'cancelled']);
        $booking->user->notify(new BookingStatusNotification($booking));


        return $this->success($booking, 'Booking cancelled successfully.');
    }
}
