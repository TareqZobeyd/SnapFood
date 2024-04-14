<?php

use App\Models\FoodDiscount;

function calculateDiscountedPrice($originalPrice, $foodDiscountId, $customDiscount)
{
    if ($customDiscount !== null) {
        return $originalPrice - ($originalPrice * ($customDiscount / 100));
    }
    if ($foodDiscountId !== null) {
        $foodDiscount = FoodDiscount::query()->find($foodDiscountId);
        if ($foodDiscount) {
            $discount = $foodDiscount->discount_percentage;
            return $originalPrice - ($originalPrice * ($discount / 100));
        }
    }
    return $originalPrice;
}
