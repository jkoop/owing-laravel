<?php

namespace App\Http\Controllers;

use App\Models\FuelPrice;
use Illuminate\Http\Request;

final class PriceHistoryController extends Controller {
    public function view() {
        $diesel = FuelPrice::where("fuel_type", "diesel")
            ->orderBy("created_at")
            ->get()
            ->pluck("price", "created_at")
            ->map(fn ($v, $k) => [$k, $v])
            ->values();

        $gasoline = FuelPrice::where("fuel_type", "gasoline")
            ->orderBy("created_at")
            ->get()
            ->pluck("price", "created_at")
            ->map(fn ($v, $k) => [$k, $v])
            ->values();

        return view("pages.price-history", compact("diesel", "gasoline"));
    }
}
