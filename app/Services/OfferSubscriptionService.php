<?php

namespace App\Services;

use App\Models\Offer;
use App\Models\OfferSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfferSubscriptionService
{
    /**
     * @return Builder[]|Collection
     */
    public function getAllSubscriptions(): Collection|array
    {
        return OfferSubscription::with('offer')->get();
    }

    /**
     * @return Collection
     */
    public function getAllOffers(): Collection
    {
        return Offer::all();
    }

    /**
     * @param Request $request
     * @return Builder|Model
     */
    public function createSubscription(Request $request): Builder|Model
    {
        $validated = $this->validateSubscription($request);

        $offer = Offer::query()->findOrFail($validated['offer_id']);
        $validated['webmaster_id'] = Auth::id();
        $validated['cost_per_click'] = $offer->cost_per_click;

        return OfferSubscription::query()->create($validated);
    }

    /**
     * @param int $subscriptionId
     * @return Builder|array|Collection|Model
     */
    public function getSubscriptionById(int $subscriptionId): Builder|array|Collection|Model
    {
        return OfferSubscription::query()->findOrFail($subscriptionId);
    }

    /**
     * @param Request $request
     * @return array
     */
    private function validateSubscription(Request $request): array
    {
        return $request->validate([
            'offer_id' => 'required|exists:offers,id',
            'cost_per_click' => 'required|numeric',
        ]);
    }
}
