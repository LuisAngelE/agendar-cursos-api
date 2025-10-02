<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\categories;
use App\Models\Course;
use App\Models\Reservation;
use App\Models\User;


class GraphicsController extends Controller
{
    public function countCategories()
    {
        $count = categories::count();

        return response()->json([
            'success' => true,
            'data' => $count,
        ], 200);
    }

    public function countCourse()
    {
        $count = Course::count();

        return response()->json([
            'success' => true,
            'data' => $count,
        ], 200);
    }

    public function countReservation()
    {
        $count = Reservation::count();

        return response()->json([
            'success' => true,
            'data' => $count,
        ], 200);
    }

    public function countUser()
    {
        $count = User::count();

        return response()->json([
            'success' => true,
            'data' => $count,
        ], 200);
    }
}
