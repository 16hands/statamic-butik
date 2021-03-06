<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Jonassiewertsen\StatamicButik\Checkout\Item;
use Jonassiewertsen\StatamicButik\Http\Models\Product;
use Jonassiewertsen\StatamicButik\Http\Models\Shipping;
use Jonassiewertsen\StatamicButik\Http\Traits\MoneyTrait;
use Jonassiewertsen\StatamicButik\Tests\TestCase;

class ItemTest extends TestCase
{
    use MoneyTrait;

    protected Product $product;

    public function setUp(): void {
        parent::setUp();

        $this->product = create(Product::class, ['stock' => 5])->first();
    }

    /** @test */
    public function it_has_a_id()
    {
        $item = new Item($this->product);

        $this->assertEquals($item->id, $this->product->slug);
    }

    /** @test */
    public function it_has_a_taxRate()
    {
        $item = new Item($this->product);

        $this->assertEquals($item->taxRate, $this->product->tax->percentage);
    }

    /** @test */
    public function it_has_a_name()
    {
        $item = new Item($this->product);

        $this->assertEquals($item->name, $this->product->title);
    }

    /** @test */
    public function it_has_a_description()
    {
        $item = new Item($this->product);

        $this->assertEquals($item->description, Str::limit($this->product->description, 100));
    }

    /** @test */
    public function it_has_a_default_quanitity_of_1()
    {
        $item = new Item($this->product);

        $this->assertEquals($item->getQuantity(), 1);
    }

    /** @test */
    public function an_item_can_be_increased()
    {
        $item = new Item($this->product);
        $item->increase();

        $this->assertEquals($item->getQuantity(), 2);
    }

    /** @test */
    public function an_item_can_max_increases_to_the_avialable_stock()
    {
        $this->product->update(['stock' => 1]);
        $item = new Item($this->product);
        $item->increase();

        $this->assertEquals(1, $item->getQuantity());
    }

    /** @test */
    public function an_item_can_be_decreased()
    {
        $item = new Item($this->product);
        $item->setQuantity(2);

        $item->decrease();

        $this->assertEquals($item->getQuantity(), 1);
    }

    /** @test */
    public function an_item_will_check_the_stock_when_increasing()
    {
        $item = new Item($this->product);
        $item->setQuantity(10);

        $this->assertEquals($item->getQuantity(), 5);
    }

    /** @test */
    public function an_item_quantity_cant_be_lover_then_one()
    {
        $item = new Item($this->product);
        $item->decrease();

        $this->assertEquals($item->getQuantity(), 1);
    }

    /** @test */
    public function an_item_has_a_total_price()
    {
        $item = new Item($this->product);

        $this->assertEquals($this->product->totalPrice, $item->totalPrice());
    }

    /** @test */
    public function multiple_prices_will_be_added_up_by_the_given_quantity()
    {
        $item = new Item($this->product);
        $item->setQuantity(3);

        $productPrice = $this->makeAmountSaveable($this->product->totalPrice);
        $total = $this->makeAmountHuman($productPrice * 3);

        $this->assertEquals($total, $item->totalPrice());
    }

    /** @test */
    public function single_shipping_costs_will_be_returned_correclty()
    {
        $item = new Item($this->product);

        $this->assertEquals($this->product->shipping->price, $item->singleShipping());
    }

    /** @test */
    public function multiple_shipping_costs_will_be_added_up_by_the_given_quantity()
    {
        $item = new Item($this->product);
        $item->setQuantity(3);

        $productShipping = $this->makeAmountSaveable($this->product->shipping_amount);
        $totalShipping = $this->makeAmountHuman($productShipping * 3);

        $this->assertEquals($totalShipping, $item->totalShipping());
    }

    /** @test */
    public function multiple_odd_shipping_costs_will_be_added_up_by_the_given_quantity()
    {
        $shipping = factory(Shipping::class)->create(['price' => '2.5']);
        $item = new Item(factory(Product::class)->create(['shipping_id' => $shipping->slug]));
        $item->setQuantity(2);

        $this->assertEquals('5,00', $item->totalShipping());
    }

    /** @test */
    public function A_new_name_will_be_reflected_on_the_item_update()
    {
        $item = new Item($this->product);

        $newDescription = 'new Description';
        $this->product->update(['description' => $newDescription]);
        Cache::flush();
        $item->update();

        $this->assertEquals($item->description, $newDescription);
    }

    /** @test */
    public function A_new_base_price_will_be_reflected_on_the_item_update()
    {
        $item = new Item($this->product);

        $newPrice = 9999;
        $this->product->update(['base_price' => $newPrice]);
        Cache::flush();
        $item->update();

        $this->assertEquals($item->base_price, $this->product->base_price);
    }

    /** @test */
    public function A_new_total_base_price_will_be_reflected_on_the_item_update()
    {
        $item = new Item($this->product);

        $oldPrice = $item->totalPrice();
        $this->product->update(['base_price' => 999]);
        Cache::flush();
        $item->update();

        $this->assertNotEquals($item->totalPrice(), $oldPrice);
    }

    /** @test */
    public function A_new_shipping_price_will_be_reflected_on_the_item_update()
    {
        $item = new Item($this->product);

        $oldShipping = $item->totalShipping();
        $this->product->shipping->update(['price' => 999]);
        Cache::flush();
        $item->update();

        $this->assertNotEquals($item->totalShipping(), $oldShipping);
    }

    /** @test */
    public function A_non_available_item_will_be_set_to_null()
    {
        $item = new Item($this->product);

        $this->product->update(['available' => false]);

        Cache::flush();
        $item->update();

        $this->assertEquals(0, $item->getQuantity());
    }
}
