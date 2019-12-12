<?php

namespace Tests\Repositories\CartSessionRepository;

use App\Repositories\CartSessionRepository;
use Tests\TestCase;

/**
 * @SuppressWarnings(PHPMD.CamelCaseMethodName)
 * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
 */
class AvailablePaymentMethodsTest extends TestCase
{
    public function test()
    {
        // given
        //

        // when
        $paymentMethods = app(CartSessionRepository::class)->availablePaymentMethods();

        // then
        $this->assertSame(['deus_ex', 'elder_scrolls', 'fallout'], $paymentMethods);
    }
}
