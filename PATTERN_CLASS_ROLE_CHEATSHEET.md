# Pattern Class Role Cheat Sheet

## 1. Prototype Pattern

### Pattern Roles

```text
Client / Trigger:
ShoePrototypeController

Prototype Registry:
ShoeRegistry

Prototype Interface:
ShoePrototype

Concrete Prototype:
Shoe
```

### Project Classes

```text
Client:
app/Http/Controllers/ShoePrototypeController.php

Registry:
app/Prototype/ShoeRegistry.php

Interface:
app/Prototype/ShoePrototype.php

Concrete class:
app/Models/Shoe.php
```

### Method Mapping

```text
ShoePrototypeController::clone()
-> user/admin starts clone process

ShoeRegistry::addItem()
-> store original shoe prototype

ShoeRegistry::getClone()
-> return cloned shoe

ShoePrototype::cloneShoe()
-> interface method

Shoe::cloneShoe()
-> concrete implementation
```

### One-Line Explanation

```text
Shoe is the concrete prototype. It implements ShoePrototype, and ShoeRegistry is used to store and clone the prototype.
```

## 2. Factory Pattern

### Pattern Roles

```text
Client:
PaymentController

Creator / Abstract Factory:
PaymentFactory

Concrete Creator:
FPXFactory
CardFactory

Product Interface:
Payment

Concrete Product:
FPXPayment
CardPayment
```

### Project Classes

```text
Client:
app/Http/Controllers/PaymentController.php

Creator:
app/Factory/PaymentFactory.php

Concrete Creators:
app/Factory/FPXFactory.php
app/Factory/CardFactory.php

Product Interface:
app/Payments/Payment.php

Concrete Products:
app/Payments/FPXPayment.php
app/Payments/CardPayment.php
```

### Method Mapping

```text
PaymentController::checkout()
-> starts payment flow

PaymentController::resolveFactory()
-> chooses factory based on payment type

PaymentFactory::createPayment()
-> abstract factory method

FPXFactory::createPayment()
-> creates FPXPayment

CardFactory::createPayment()
-> creates CardPayment

Payment::pay()
-> product interface method

FPXPayment::pay()
CardPayment::pay()
-> concrete product methods
```

### One-Line Explanation

```text
PaymentFactory is the creator. FPXFactory and CardFactory are concrete creators. They create concrete products FPXPayment and CardPayment through the Payment interface.
```

## 3. Builder Pattern

### Builder Types In This Project

```text
There are 3 Builder implementations:

1. AdminShoeBuilder
2. AdminShoeSkuBuilder
3. CustomerShoeBuilder
```

## 3.1 Admin Shoe Builder

### Pattern Roles

```text
Client:
ShoeController

Director:
ShoeDirector

Builder Interface:
ShoeBuilderInterface

Concrete Builder:
AdminShoeBuilder

Product:
Shoe data array
```

### Project Classes

```text
Client:
app/Http/Controllers/ShoeController.php

Director:
app/Services/Builders/Directors/ShoeDirector.php

Builder Interface:
app/Services/Builders/Interfaces/ShoeBuilderInterface.php

Concrete Builder:
app/Services/Builders/Builders/AdminShoeBuilder.php

Product Model:
app/Models/Shoe.php
```

### Method Mapping

```text
ShoeController::createShoe()
-> starts admin create shoe flow

ShoeDirector::buildShoe()
-> controls build steps

ShoeBuilderInterface::setBrand()
ShoeBuilderInterface::setName()
ShoeBuilderInterface::setDescription()
ShoeBuilderInterface::setBasePrice()
-> builder interface methods

AdminShoeBuilder::reset()
-> clears old shoe data

AdminShoeBuilder::setBrand()
AdminShoeBuilder::setName()
AdminShoeBuilder::setDescription()
AdminShoeBuilder::setBasePrice()
-> sets each shoe field

AdminShoeBuilder::build()
-> returns completed shoe data array

Shoe::create()
-> final shoe is saved
```

### One-Line Explanation

```text
AdminShoeBuilder builds the base shoe data before the system saves a new shoe product.
```

## 3.2 Admin SKU Builder

### Pattern Roles

```text
Client:
ShoeController

Director:
ShoeSkuDirector

Builder Interface:
ShoeSkuBuilderInterface

Concrete Builder:
AdminShoeSkuBuilder

Product:
SKU / variation data array
```

### Project Classes

```text
Client:
app/Http/Controllers/ShoeController.php

Director:
app/Services/Builders/Directors/ShoeSkuDirector.php

Builder Interface:
app/Services/Builders/Interfaces/ShoeSkuBuilderInterface.php

Concrete Builder:
app/Services/Builders/Builders/AdminShoeSkuBuilder.php

Product Model:
app/Models/ShoeVariations.php
```

### Method Mapping

```text
ShoeController::createSkus()
-> starts admin create SKU flow

ShoeSkuDirector::buildSku()
-> controls build steps

ShoeSkuBuilderInterface::addSku()
-> builder interface method

AdminShoeSkuBuilder::reset()
-> clears old variation data

AdminShoeSkuBuilder::addSku()
-> validates option names, checks duplicate variation, builds SKU data

AdminShoeSkuBuilder::generateSkuCode()
-> generates SKU code from shoe brand, shoe name, and attributes

AdminShoeSkuBuilder::build()
-> returns completed SKU data array

ShoeVariations::create()
-> final SKU is saved
```

### One-Line Explanation

```text
AdminShoeSkuBuilder builds SKU variation data, validates options, checks duplicates, and generates SKU code.
```

## 3.3 Customer Shoe Builder

### Pattern Roles

```text
Client:
CartController

Director:
CustomerShoeDirector

Builder Interface:
CustomerShoeBuilderInterface

Concrete Builder:
CustomerShoeBuilder

Product:
Shoe
```

### Project Classes

```text
Client:
app/Http/Controllers/CartController.php

Director:
app/Services/Builders/Directors/CustomerShoeDirector.php

Builder Interface:
app/Services/Builders/Interfaces/CustomerShoeBuilderInterface.php

Concrete Builder:
app/Services/Builders/Builders/CustomerShoeBuilder.php

Product:
app/Models/Shoe.php
```

### Method Mapping

```text
CartController::addToCart()
-> starts customer add-to-cart flow

CustomerShoeDirector::buildProduct()
-> controls build steps

CustomerShoeBuilderInterface::setAttribute()
-> builder interface method

CustomerShoeBuilder::reset()
-> creates empty product

CustomerShoeBuilder::setAttribute()
-> adds shoe_id, variation_id, quantity, sku_code, size, color

CustomerShoeBuilder::getProduct()
-> returns completed Shoe object

CartService::addItem()
-> final product is added to cart
```

### One-Line Explanation

```text
CartController is the client. CustomerShoeDirector controls the process. CustomerShoeBuilder is the concrete builder that builds the selected Shoe object step by step.
```

## Super Quick Oral Script

```text
For Prototype, the interface is ShoePrototype and the concrete prototype is Shoe.

For Factory, the creator is PaymentFactory, the concrete creators are FPXFactory and CardFactory, the product interface is Payment, and the concrete products are FPXPayment and CardPayment.

For Builder, there are 3 concrete builders:
AdminShoeBuilder builds base shoe product data.
AdminShoeSkuBuilder builds SKU variation data.
CustomerShoeBuilder builds the selected customer shoe object before add to cart.
```
