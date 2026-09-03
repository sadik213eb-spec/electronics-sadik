<?php

namespace App\Filament\Resources\CheckoutOffers\Pages;

use App\Filament\Resources\CheckoutOffers\CheckoutOfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCheckoutOffer extends EditRecord
{
    protected static string $resource = CheckoutOfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
